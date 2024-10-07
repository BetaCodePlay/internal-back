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
            v-loading="loading"
            element-loading-text="Cargando..."
            element-loading-spinner="el-icon-loading"
            ref="transactionsTable"
            class="mt-3"
            :value="items"
            responsiveLayout="scroll"
        >
            <Column
                v-for="col of columns"
                :field="col.field"
                :header="col.header"
                :key="`key-${col.field}-${force}`"
            >
                <template #body="slotProps">
                    <div class="text-center" v-if="col.field == 'date'">
                        <strong>{{ slotProps.data.date }}</strong>
                    </div>
                    <div
                        class="text-center"
                        :class="{
                            negative: slotProps.data.new_amount.includes('-'),
                            positive: slotProps.data.new_amount.includes('+'),
                        }"
                        v-else-if="col.field == 'new_amount'"
                    >
                        {{ slotProps.data.new_amount }}
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

        <Paginator
            :rows="perPage"
            :totalRecords="totalRecords"
            :rowsPerPageOptions="[10, 20, 30]"
            @page="onPageChange"
        >
        </Paginator>
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
                { field: "date", header: "Fecha" },
                { field: "from", header: "Agente" },
                { field: "to", header: "Cuenta destino" },
                { field: "new_amount", header: "Monto" },
                { field: "balance", header: "Balance" },
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
            this.perPage = event.rows;
            this.fetchData();
        },
        async fetchData() {
            this.loading = true;
            const {
                query,
                daterange,
                typeUser,
                typeTransaction,
                selectedTimezone,
                timeStart,
                timeEnd
            } = this.filters;

            if (daterange[1]) {
                const startDate = moment(daterange[0]).format("YYYY-MM-DD");
                const endDate = moment(daterange[1]).format("YYYY-MM-DD");
                const userId = window.authUserId;

                try {
                    const { data } = await axios.get(
                        `/agents/${userId}/transactions`,
                        {
                            params: {
                                startDate,
                                endDate,
                                typeUser,
                                query,
                                typeTransaction,
                                timezone: selectedTimezone,
                                per_page: this.perPage,
                                page: this.currentPage,
                                timeStart,
                                timeEnd
                            },
                        }
                    );

                    this.items = data.data;
                    this.totalRecords = data.total;
                    /*console.error(error);*/
                } finally {
                    setTimeout(() => {
                        this.loading = false;
                    }, 500);
                }
            }
        },
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
.negative {
    color: #e71818;
    font-weight: 500;
}
.positive {
    color: #D7FE62;
    font-weight: 500;
}
</style>
