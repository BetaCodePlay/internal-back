<template>
    <div>
        <components-reports-filters
            title="Transacciones"
            v-model="filters"
            @change="onChange"
        />
        <DataTable
            class="mt-3"
            :value="items"
            responsiveLayout="stack"
            :expandedRows.sync="expandedRows"
            @row-expand="onRowExpand"
            @row-collapse="onRowCollapse"
        >
            <Column :expander="true" :headerStyle="{ width: '1rem' }" />
            <Column
                v-for="col of columns"
                :field="col.field"
                :header="col.header"
                :key="col.field"
            >
                <template #body="slotProps">
                    <div class="text-right" v-if="col.field == 'played'">
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
                    <DataTable :value="items">
                        <Column field="won" header="Name"></Column>
                        <Column field="won" header="Played"></Column>
                        <Column field="won" header="Won"></Column>
                        <Column field="won" header="Profit"></Column>
                        <Column field="won" header="Commission"></Column>
                    </DataTable>
                </div>
            </template>
            <ColumnGroup type="footer">
                <Row>
                    <Column
                        footer="Total a cobrar:"
                        :colspan="6"
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
            expandedRows: [],
            loading: false,
            filters: {
                selectedCity: "",
                daterange: [
                    new Date(new Date().setDate(new Date().getDate() - 30)),
                    new Date(),
                ],
            },
            items: [],
            columns: [
                { field: "type", header: "Categoría" },
                { field: "name", header: "Nombre" },
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
        onRowExpand(event) {},
        onRowCollapse(event) {},
        onChange() {
            this.FetchData();
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
                        )}`
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
</style>
