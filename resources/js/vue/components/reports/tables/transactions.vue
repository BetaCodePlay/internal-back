<template>
    <div>
        <components-reports-filters
            title="Transacciones"
            v-model="filters"
            @change="onChange"
            @export="exportData"
            :showTypeUser="true"
            :showTypeTransaction="true"
            :showTimezone="true"
        />
        <DataTable
            ref="transactionsTable"
            class="mt-3"
            :value="items"
            responsiveLayout="scroll"
            paginator="false"
            :totalRecords="totalRecords"
            :rows="perPage"
            :currentPage="currentPage"
            :onPage="onPageChange"
            :rowsPerPageOptions="[10, 20, 50]"
        >
            <Column
                v-for="col of columns"
                :field="col.field"
                :header="col.header"
                :key="`key-${col.field}-${force}`"
            >
                <template #body="slotProps">
                    <div class="text-center" v-if="col.field == 'date'">
                        <strong>{{
                                moment(slotProps.data.date).format("YYYY-MM-DD")
                            }}</strong>
                    </div>
                    <div
                        class="text-center"
                        v-else-if="col.field == 'new_amount'"
                    >
                        <div v-html="slotProps.data.new_amount"></div>
                    </div>
                    <div class="text-center" v-else-if="col.field == 'from'">
                        {{ slotProps.data.data.from }}
                    </div>
                    <div class="text-center" v-else-if="col.field == 'to'">
                        {{ slotProps.data.data.to }}
                    </div>

                    <div class="text-center" v-else>
                        {{ slotProps.data[col.field] }}
                    </div>
                </template>
            </Column>

            <div class="loading-style" v-if="loading"></div>
        </DataTable>

        <template>
            <div class="card">
                <Paginator :rows="perPage" :totalRecords="totalRecords" :rowsPerPageOptions="[10, 20, 30]">
                    <template #start="slotProps">
                        Page: {{ slotProps.state.page }}
                        First: {{ slotProps.state.first }}
                        Rows: {{ slotProps.state.rows }}
                    </template>
                    <template #end>
                        <Button type="button" icon="pi pi-search" />
                    </template>
                </Paginator>
            </div>
        </template>
    </div>
</template>
<script>
import axios from "axios";
import moment from "moment";

export default {
    data() {
        return {
            moment,
            force: 0,
            expandedRows: [],
            loading: false,
            currentPage: 1,
            perPage: 10,
            totalRecords: 0,
            filters: {
                query: "",
                daterange: [
                    new Date(new Date().setDate(new Date().getDate() - 30)),
                    new Date(),
                ],
                selectedTimezone: window.timezone ?? "",
                typeUser: "all",
                typeTransaction: "all",
                timezone: "all",
            },
            items: [],
            columns: [
                {field: "date", header: "Fecha"},
                {field: "from", header: "Agente"},
                {field: "to", header: "Cuenta destino"},
                {field: "new_amount", header: "Monto"},
                {field: "balance", header: "Balance"},
            ],
        };
    },
    computed: {
        totalProfit() {
            let total = 0;
            for (let sale of this.items) {
                total += parseFloat(sale.profit);
            }

            return total.formatMoney();
        },
        totalCommision() {
            let total = 0;
            for (let sale of this.items) {
                total += parseFloat(sale.commission);
            }

            return total.formatMoney();
        },
    },
    methods: {
        exportXLS() {
            let filename = `Reporte-Financiero-${moment().format(
                "DD-MM-YYYY"
            )}.xlsx`;
            let data = this.items;
            var ws = XLSX.utils.json_to_sheet(data);
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Reporte Financiero");
            XLSX.writeFile(wb, filename);
        },
        exportData(type) {
            console.log(type);
            switch (type) {
                case "excel":
                    this.exportXLS();
                    break;
            }
        },
        onChange() {
            this.fetchData();
        },
        onPageChange(event) {
            this.currentPage = event.page + 1;
            this.fetchData();
        },
        fetchData1() {
            this.loading = true;
            const {daterange, typeUser, typeTransaction, selectedTimezone} = this.filters;

            if (daterange[1]) {
                const startDate = moment(daterange[0]).format("YYYY-MM-DD");
                const endDate = moment(daterange[1]).format("YYYY-MM-DD");
                const userId = window.authUserId;

                axios
                    .get(`/agents/${userId}/transactions`, {
                        params: {
                            startDate,
                            endDate,
                            typeUser,
                            typeTransaction,
                            timezone: selectedTimezone,
                            per_page: this.perPage,
                            page: this.currentPage
                        }
                    })
                    .then(({data}) => {
                        this.items = data.data;
                        this.totalRecords = data.total;

                        console.log(data, data.total, data.per_page);
                        this.loading = false;
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            }
        },
        async fetchData() {
            this.loading = true;
            const {daterange, typeUser, typeTransaction, selectedTimezone} = this.filters;

            if (daterange[1]) {
                const startDate = moment(daterange[0]).format("YYYY-MM-DD");
                const endDate = moment(daterange[1]).format("YYYY-MM-DD");
                const userId = window.authUserId;

                try {
                    const {data} = await axios.get(`/agents/${userId}/transactions`, {
                        params: {
                            startDate,
                            endDate,
                            typeUser,
                            typeTransaction,
                            timezone: selectedTimezone,
                            per_page: this.perPage,
                            page: this.currentPage
                        }
                    });

                    this.items = data.data;
                    this.totalRecords = data.total;

                    console.log(data, data.total, data.per_page);
                } catch (error) {
                    console.error(error);
                } finally {
                    this.loading = false;
                }
            }
        }

    },
    mounted() {
        this.fetchData();
    },
};
</script>
<style>
.p-column-header-content {
    justify-content: center;
}

.orders-subtable {
    border: 1px solid #8080800f;
    border-radius: 8px;
    padding: 10px;
}
</style>
