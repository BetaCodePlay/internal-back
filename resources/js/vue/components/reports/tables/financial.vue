<template>
    <div>
        
        <components-reports-filters
            title="Reporte Financiero"
            v-model="filters"
            @change="onChange"
            @export="exportData"
            
            :showUser="true"
            :showProvider="true"
            :showTimezone="true"
        />
        <DataTable
            ref="financialTable"
            class="mt-3"
            :value="items"
            responsiveLayout="scroll"
            :expandedRows.sync="expandedRows"
            @row-expand="onRowExpand"
            @row-collapse="onRowCollapse"
        >
            <Column :expander="true" :headerStyle="{ width: '1rem' }" />
            <Column
                v-for="col of columns"
                :field="col.field"
                :header="col.header"
                :key="`key-${col.field}-${force}`"
            >
                <template #body="slotProps">
                    <div class="text-center" v-if="col.field == 'category'">
                        <strong>{{ slotProps.data.category }}</strong>
                    </div>
                    <div class="text-right" v-else-if="col.field == 'played'">
                        {{ slotProps.data.played.formatMoney() }}
                    </div>
                    <div class="text-right" v-else-if="col.field == 'won'">
                        {{ slotProps.data.won.formatMoney() }}
                    </div>
                    <div class="text-right" v-else-if="col.field == 'profit'">
                        {{ slotProps.data.profit.formatMoney() }}
                    </div>
                    <div
                        class="text-right"
                        v-else-if="col.field == 'commission'"
                    >
                        {{ slotProps.data.commission.formatMoney() }}
                    </div>
                    <div class="text-center" v-else>
                        {{ slotProps.data[col.field] }}
                    </div>
                </template>
            </Column>
            <template #expansion="slotProps">
                <div class="orders-subtable">
                    <h4 class="ml-3">Detalle de: {{slotProps.data.category}}</h4>
                    <DataTable :value="slotProps.data.items">
                        <Column field="name" header="Juego">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.name }}
                                </div>
                            </template>
                        </Column>
                        <Column field="provider" header="Proveedor">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.name }}
                                </div>
                            </template></Column
                        >
                        <Column field="played" header="Jugado">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.played.formatMoney() }}
                                </div>
                            </template></Column
                        >
                        <Column field="won" header="Ganado">
                            <template #body="slotProps">
                                <div class="text-center">
                                   {{ slotProps.data.won.formatMoney() }}
                                </div>
                            </template></Column
                        >
                        <Column field="profit" header="Netwin">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.profit.formatMoney() }}
                                </div>
                            </template></Column
                        >
                        <Column field="commission" header="Comision">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.commission.formatMoney() }}
                                </div>
                            </template></Column
                        >
                    </DataTable>
                </div>
            </template>
            <ColumnGroup type="footer">
                <Row>
                    <Column
                        footer="Total a cobrar:"
                        :colspan="5"
                        :footerStyle="{ 'text-align': 'right' }"
                    />
                    <Column
                        :footer="totalCommision"
                        :footerStyle="{ 'text-align': 'right' }"
                    />
                </Row>
            </ColumnGroup>

            <div class="loading-style" v-if="loading"></div>
        </DataTable>
    </div>
</template>
<script>
import axios from "axios";
import moment from "moment";
export default {
    data() {
        return {
            force: 0,
            expandedRows: [],
            loading: false,
            filters: {
                query: "",
                daterange: [
                    new Date(new Date().setDate(new Date().getDate() - 30)),
                    new Date(),
                ],
                selectedUser: "",
                selectedTimezone: window.timezone ?? "",
                selectedProvider: "",
            },
            items: [],
            columns: [
                { field: "category", header: "Categoría" },
                { field: "played", header: "Jugado" },
                { field: "won", header: "Ganado" },
                { field: "profit", header: "NetWin" },
                { field: "commission", header: "Comisión" },
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
        onRowExpand(event) {
            //this.expandedRows = null;
            this.expandedRows = []
            this.expandedRows.push(event.data)
            this.getDetailsCategory(event.data.category);
        },
        onRowCollapse(event) {},
        onChange() {
            this.FetchData();
        },
        getDetailsCategory(category) {
            this.loading = true;
            axios
                .get(
                    `/agents/reports/financial-state-data-v2-category/${
                        window.authUserId
                    }/${moment(this.filters.daterange[0]).format(
                        "YYYY-MM-DD"
                    )}/${moment(this.filters.daterange[1]).format(
                        "YYYY-MM-DD"
                    )}/${category}?timezone=${
                        this.filters.selectedTimezone
                    }&provider=${this.filters.selectedProvider}&child=${
                        this.filters.selectedUser
                    }&text=${this.filters.query}`
                )
                .then((resp) => {
                    this.$nextTick(() => {
                        this.items.find((i) => i.category == category).items =
                            resp.data.data;
                        this.force++;
                        this.loading = false;
                    });
                })
                .catch(() => {
                    this.loading = false;
                });
        },
        FetchData() {
            if (this.filters.daterange[1]) {
                this.loading = true;
                axios
                    .get(
                        `/agents/reports/financial-state-data-v2/${
                            window.authUserId
                        }/${moment(this.filters.daterange[0]).format(
                            "YYYY-MM-DD"
                        )}/${moment(this.filters.daterange[1]).format(
                            "YYYY-MM-DD"
                        )}?timezone=${this.filters.selectedTimezone}&provider=${
                            this.filters.selectedProvider
                        }&child=${this.filters.selectedUser}&text=${
                            this.filters.query
                        }`
                    )
                    .then((resp) => {
                        this.items = resp.data.data;
                        this.loading = false;
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            }
        },
    },
    mounted() {
        this.FetchData();
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
