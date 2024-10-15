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
            v-loading="loading"
            element-loading-text="Cargando..."
            element-loading-spinner="el-icon-loading"
            ref="financialTable"
            class="mt-3"
            :value="items"
            responsiveLayout="scroll"
            :expandedRows.sync="expandedRows"
            @row-expand="onRowExpand"
            @row-collapse="onRowCollapse"
        >
            <Column :expander="true" :headerStyle="{ width: '1rem' }"/>
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
                        {{ slotProps.data.played }}
                    </div>
                    <div class="text-right" v-else-if="col.field == 'won'">
                        {{ slotProps.data.won }}
                    </div>
                    <div class="text-right" v-else-if="col.field == 'profit'">
                        {{ slotProps.data.profit }}
                    </div>
                    <div
                        class="text-right"
                        v-else-if="col.field == 'commission'"
                    >
                        {{ slotProps.data.commission }}
                    </div>
                    <div class="text-center" v-else>
                        {{ slotProps.data[col.field] }}
                    </div>
                </template>
            </Column>
            <template #expansion="slotProps">
                <div class="orders-subtable">
                    <h4 class="ml-3">
                        Detalle de: {{ slotProps.data.category }}
                    </h4>
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
                                    {{ slotProps.data.provider }}
                                </div>
                            </template>
                        </Column
                        >
                        <Column field="played" header="Jugado">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.played }}
                                </div>
                            </template>
                        </Column
                        >
                        <Column field="won" header="Ganado">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.won }}
                                </div>
                            </template>
                        </Column
                        >
                        <Column field="profit" header="Netwin">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{ slotProps.data.profit }}
                                </div>
                            </template>
                        </Column
                        >
                        <Column field="commission" header="Comision">
                            <template #body="slotProps">
                                <div class="text-center">
                                    {{
                                        slotProps.data.commission
                                    }}
                                </div>
                            </template>
                        </Column
                        >
                    </DataTable>
                </div>
            </template>
            <ColumnGroup type="footer">
                <Row>
                    <Column
                        footer="Total de la ganancia:"
                        :colspan="5"
                        :footerStyle="{ 'text-align': 'right' }"
                    />
                    <Column
                        :footer="totalCommission"
                        :footerStyle="{ 'text-align': 'right' }"
                    />
                </Row>
            </ColumnGroup>
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
            totalCommission: 0,
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
                {field: "category", header: "Categoría"},
                {field: "played", header: "Jugado"},
                {field: "won", header: "Ganado"},
                {field: "profit", header: "NetWin"},
                {field: "commission", header: "Comisión"},
            ],
        };
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
            this.expandedRows = [];
            this.expandedRows.push(event.data);
            this.getDetailsCategory(event.data.category);
        },
        onRowCollapse(event) {
        },
        onChange() {
            this.fetchData();
        },
        getDetailsCategory(category) {
            this.loading = true;

            const { authUserId } = window;
            const { daterange, selectedTimezone, selectedProvider, selectedUser, query, timeStart, timeEnd } = this.filters;
            const startDate = moment(daterange[0]).format("YYYY-MM-DD");
            const endDate = moment(daterange[1]).format("YYYY-MM-DD");

            const url = `/agents/reports/financial-state-data-v2-category/${authUserId}/${startDate}/${endDate}/${category}`;

            const params = {
                timezone: selectedTimezone,
                provider: selectedProvider,
                child: selectedUser,
                text: query,
                timeStart,
                timeEnd
            };

            axios.get(url, { params })
                .then((resp) => {
                    this.$nextTick(() => {
                        const categoryItem = this.items.find((i) => i.category === category);
                        if (categoryItem) {
                            categoryItem.items = resp.data.data;
                            this.force++;
                        }
                    });
                })
                .catch((error) => {
                    console.error("Error fetching category details:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        fetchData() {
            if (this.filters.daterange[1]) {
                this.loading = true;

                const { authUserId } = window;
                const { daterange, selectedTimezone, selectedProvider, selectedUser, query, timeStart, timeEnd } = this.filters;
                const startDate = moment(daterange[0]).format("YYYY-MM-DD");
                const endDate = moment(daterange[1]).format("YYYY-MM-DD");

                const url = `/agents/reports/financial-state-data-v2/${authUserId}/${startDate}/${endDate}`;

                const params = {
                    timezone: selectedTimezone,
                    provider: selectedProvider,
                    child: selectedUser,
                    text: query,
                    timeStart,
                    timeEnd
                };

                axios.get(url, { params })
                    .then((resp) => {
                        this.items = resp.data.data;
                        this.totalCommission = resp.data.totalCommission;
                    })
                    .catch((error) => {
                        console.error("Error fetching financial state data:", error);
                    })
                    .finally(() => {
                        setTimeout(() => {
                            this.loading = false;
                        }, 500);
                    });
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
