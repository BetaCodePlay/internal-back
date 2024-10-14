<template>
    <div>
        <components-reports-filters
            title="Reporte Financiero por Usuarios"
            v-model="filters"
            @change="onChange"
            @export="exportData"
            :showUser="true"
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
            paginator
            :rows="1000"
        >
            <template #empty>{{ emptyMessage }}</template>


            <Column
                v-for="col of columns"
                :field="col.field"
                :header="col.header"
                :sortable="true"
                :key="`key-${col.field}-${force}`"
            >
                <template #body="slotProps">
                    <div class="text-center" v-if="col.field === 'username'">
                        <strong>{{ slotProps.data.username }}</strong>
                    </div>
                    <div class="text-center" v-if="col.field === 'type_user'">
                        <strong>{{ slotProps.data.type_user }}</strong>
                    </div>
                    <div class="text-center" v-else-if="col.field === 'played'">
                        {{ slotProps.data.played }}
                    </div>
                    <div class="text-center" v-else-if="col.field === 'won'">
                        {{ slotProps.data.won }}
                    </div>
                    <div class="text-center" v-else-if="col.field === 'profit'">
                        {{ slotProps.data.profit }}
                    </div>
                </template>
            </Column>
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
            emptyMessage: 'No hay datos disponibles.',
            filters: {
                query: "",
                daterange: [
                    new Date(new Date().setDate(new Date().getDate() - 30)),
                    new Date(),
                ],
                selectedUser: "",
                selectedTimezone: window.timezone ?? ""
            },
            items: [],
            columns: [
                {field: "username", header: "Username"},
                {field: "type_user", header: "Tipo"},
                {field: "played", header: "Jugado"},
                {field: "won", header: "Ganado"},
                {field: "profit", header: "NetWin"}
            ]
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
            switch (type) {
                case "excel":
                    this.exportXLS();
                    break;
            }
        },
        onChange() {
            this.fetchData();
        },
        fetchData() {
            if (this.filters.daterange[1]) {
                this.loading = true;

                const {authUserId} = window;
                const {daterange, selectedTimezone, selectedUser, query, timeStart, timeEnd, timeDual} = this.filters;

                if (query && query.length < 3) {
                    this.loading = false;
                    return;
                }

                const startDate = moment(daterange[0]).format("YYYY-MM-DD");
                const endDate = moment(daterange[1]).format("YYYY-MM-DD");

                const url = `/agents/reports/user-financial-report/${authUserId}/${startDate}/${endDate}`;

                const params = {
                    timezone: selectedTimezone,
                    child: selectedUser,
                    text: query,
                    timeStart,
                    timeEnd,
                    timeDual
                };

                axios.get(url, {params})
                    .then((resp) => {
                        this.items = resp.data.data;
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
.custom-empty-message {
    text-align: center;
    padding: 1em;
    font-size: 1.2em;
}
</style>
