<template>
    <div>
        <Card>
            <template #content>
                <div class="row">
                    <div class="col-md-8 pt-2 mt-2">
                        <h4>{{ title }}</h4>
                    </div>
                    <div class="col-md-2 mt-2">
                        <i
                            class="pi pi-search"
                            style="position: absolute; top: 12px; left: 25px"
                        ></i>
                        <input
                            type="text"
                            placeholder="Buscar"
                            v-model="value.query"
                            @input="updateFilters"
                            style="
                                color: white;
                                background: #474747;
                                height: 2.7rem;
                                padding-left: 35px;
                                border-radius: 8px;
                                border: none;
                            "
                            class="form-control"
                        />
                    </div>
                    <div class="col-md-2 pt-1 mt-2">
                        <div class="row">
                            <div class="col-9">
                                <div class="dropdown">
                                    <button
                                        class="btn"
                                        type="button"
                                        style="color: white"
                                        data-toggle="dropdown"
                                        aria-expanded="false"
                                        data-offset="10,20"
                                        data-auto-close="false"
                                    >
                                        Filtrar contenido
                                        <i class="pi pi-angle-down ml-3"></i>
                                    </button>
                                    <div
                                        class="dropdown-menu dropdown-menu-right custom-report-dropdown"
                                    >
                                        <form style="min-width: 400px">
                                            <Card style="margin-top: 0px">
                                                <template #content>
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <label
                                                            >Fechas</label
                                                            >
                                                            <el-date-picker
                                                                v-model="
                                                                    value.daterange
                                                                "
                                                                type="daterange"
                                                                align="right"
                                                                size="large"
                                                                @input="
                                                                    updateFilters
                                                                "
                                                                range-separator="-"
                                                                start-placeholder="Fecha Inicial"
                                                                end-placeholder="Fecha Final"
                                                            >
                                                            </el-date-picker>
                                                        </div>

                                                        <div class="col-6 mb-3" v-if="showTime">
                                                            <label>Hora Inicial</label><br>
<!--                                                            <el-time-select
                                                                v-model="value.timeStart"
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                :picker-options="{start: '00:00', step: '00:15', end: '23:59'}"
                                                                placeholder="Select time">
                                                            </el-time-select>-->

                                                            <el-time-picker
                                                                v-model="value.timeStart"
                                                                arrow-control
                                                                placeholder="Select time"
                                                            />
                                                        </div>

                                                        <div class="col-6 mb-3" v-if="showTime">
                                                            <label>Hora Final</label><br>
<!--                                                            <el-time-select
                                                                v-model="value.timeEnd"
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                :picker-options="{start: '00:00', step: '00:15', end: '23:59'}"
                                                                placeholder="Select time">
                                                            </el-time-select>-->

                                                            <el-time-picker
                                                                v-model="value.timeEnd"
                                                                arrow-control
                                                                placeholder="Select time"
                                                            />
                                                        </div>
                                                        <div
                                                            class="col-12 mb-3"
                                                            v-if="showTimezone"
                                                        >
                                                            <label
                                                            >Zona
                                                                Horaria</label
                                                            >
                                                            <Dropdown
                                                                v-model="
                                                                    value.selectedTimezone
                                                                "
                                                                :options="
                                                                    timezones
                                                                "
                                                                class="form-control"
                                                                optionLabel="timezone"
                                                                optionValue="timezone"
                                                                dataKey="timezone"
                                                                :showClear="
                                                                    true
                                                                "
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                placeholder="Selecciona zona horaria"
                                                                :filter="true"
                                                                filterPlaceholder="Buscar zona horaria"
                                                            />
                                                        </div>
                                                        <div
                                                            class="col-12 mb-3"
                                                            v-if="showProvider"
                                                        >
                                                            <label
                                                            >Proveedor</label
                                                            >
                                                            <Dropdown
                                                                v-model="
                                                                    value.selectedProvider
                                                                "
                                                                :options="
                                                                    providers
                                                                "
                                                                class="form-control"
                                                                optionLabel="provider"
                                                                optionValue="provider"
                                                                dataKey="provider"
                                                                :showClear="
                                                                    true
                                                                "
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                placeholder="Seleccionar proveedor"
                                                                :filter="true"
                                                                filterPlaceholder="Buscar Proveedor"
                                                            />
                                                        </div>
                                                        <div
                                                            class="col-12 mb-3"
                                                            v-if="showUser"
                                                        >
                                                            <label
                                                            >Usuario</label
                                                            >
                                                            <Dropdown
                                                                v-model="
                                                                    value.selectedUser
                                                                "
                                                                :options="
                                                                    childs
                                                                "
                                                                class="form-control"
                                                                optionLabel="username"
                                                                optionValue="id"
                                                                dataKey="id"
                                                                :showClear="
                                                                    true
                                                                "
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                placeholder="Seleccionar usuario"
                                                                :filter="true"
                                                                filterPlaceholder="Buscar usuario"
                                                            />
                                                        </div>

                                                        <div
                                                            class="col-12 mb-3"
                                                            v-if="
                                                                showTypeTransaction
                                                            "
                                                        >
                                                            <label
                                                            >Tipo de
                                                                Transacción</label
                                                            >
                                                            <Dropdown
                                                                v-model="
                                                                    value.typeTransaction
                                                                "
                                                                :options="
                                                                    typeTransactions
                                                                "
                                                                class="form-control"
                                                                optionLabel="label"
                                                                optionValue="value"
                                                                dataKey="value"
                                                                :showClear="
                                                                    true
                                                                "
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                placeholder="Seleccionar tipo de transacción"
                                                            />
                                                        </div>
                                                        <div
                                                            class="col-12 mb-3"
                                                            v-if="showTypeUser"
                                                        >
                                                            <label
                                                            >Tipo de
                                                                Usuario</label
                                                            >
                                                            <Dropdown
                                                                v-model="
                                                                    value.typeUser
                                                                "
                                                                :options="
                                                                    typeUsers
                                                                "
                                                                class="form-control"
                                                                optionLabel="label"
                                                                optionValue="value"
                                                                dataKey="value"
                                                                :showClear="
                                                                    true
                                                                "
                                                                @change="
                                                                    updateFilters
                                                                "
                                                                placeholder="Seleccionar tipo de usuario"
                                                            />
                                                        </div>
                                                    </div>
                                                </template>
                                            </Card>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="dropdown">
                                    <button
                                        class="btn"
                                        type="button"
                                        style="color: white"
                                        data-toggle="dropdown"
                                        aria-expanded="false"
                                        data-offset="10,20"
                                    >
                                        <i
                                            class="fa-solid fa-ellipsis-vertical"
                                        ></i>
                                    </button>
                                    <ul
                                        class="dropdown-menu dropdown-menu-right custom-report-dropdown"
                                    >
                                        <li>
                                            <a
                                                @click="exportData('excel')"
                                                class="dropdown-item"
                                                href="javascript:void(0)"
                                            >Export Excel</a
                                            >
                                        </li>
                                        <!--  <li>
                                            <a @click="exportData('pdf')" class="dropdown-item" href="javascript:void(0)"
                                                >Export PDF</a
                                            >
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>
<script>
import axios from "axios";

export default {
    props: {
        value: {
            type: Object,
            default: {
                query: "",
                daterange: [],
                timeStart: "",
                timeEnd: "",
                selectedUser: "",
                selectedTimezone: "",
                selectedProvider: "",
                typeUser: "all",
                typeTransaction: "all",
            },
        },
        title: {
            type: String,
            default: "",
        },
        showProvider: {
            type: Boolean,
            default: false,
        },
        showTime: {
            type: Boolean,
            default: true,
        },
        showUser: {
            type: Boolean,
            default: false,
        },

        showTypeTransaction: {
            type: Boolean,
            default: false,
        },
        showTimezone: {
            type: Boolean,
            default: false,
        },
        showTypeUser: {
            type: Boolean,
            default: false,
        },
    },
    mounted() {
        document
            .querySelector(".dp-link")
            .addEventListener("click", function (event) {
                event.preventDefault();
            });
    },
    data() {
        return {
            pickerOptions: {
                shortcuts: [
                    {
                        text: "Última semana",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 7
                            );
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Último mes",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 30
                            );
                            picker.$emit("pick", [start, end]);
                        },
                    },
                    {
                        text: "Últimos 3 meses",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 90
                            );
                            picker.$emit("pick", [start, end]);
                        },
                    },
                ],
            },
            childs: [],
            typeUsers: [
                {label: "Todos", value: "all"},
                {label: "Agente", value: "agent"},
                {label: "Usuario", value: "user"},
            ],
            typeTransactions: [
                {label: "Todos", value: "all"},
                {label: "Cargo", value: "credit"},
                {label: "Descarga", value: "debit"},
            ],
            providers: [],
            es: {
                firstDayOfWeek: 1,
                dayNames: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
                dayNamesShort: [
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mié",
                    "Jue",
                    "Vie",
                    "Sáb",
                ],
                dayNamesMin: ["D", "L", "M", "X", "J", "V", "S"],
                monthNames: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
                monthNamesShort: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                today: "Hoy",
                clear: "Borrar",
                weekHeader: "Sm",
            },
            timezones: [],
        };
    },
    methods: {
        getProviders() {
            axios.get("/agents/reports/get-providers").then((resp) => {
                this.providers = resp.data.data;
            });
        },
        getTimezones() {
            axios.get("/agents/reports/get-timezones").then((resp) => {
                this.timezones = resp.data.data;
            });
        },
        getChilds() {
            axios.get("/agents/reports/get-childrens").then((resp) => {
                this.childs = resp.data.data;
            });
        },
        updateFilters() {
            this.$emit("change", this.value);
        },
        exportData(type) {
            this.$emit("export", type);
        },
    },
    mounted() {
        this.getProviders();
        this.getTimezones();
        this.getChilds();
    },
};
</script>
<style>
.p-calendar .p-datepicker {
    min-width: unset;
}

.custom-report-dropdown {
    min-width: unset;
    padding: 5px;
    color: white;
    border-radius: 8px;
    background-color: #1e1e1e;
    -webkit-box-shadow: 10px 10px 38px 22px rgba(0, 0, 0, 0.71);
    -moz-box-shadow: 10px 10px 38px 22px rgba(0, 0, 0, 0.71);
    box-shadow: 10px 10px 38px 22px rgba(0, 0, 0, 0.71);
}

.custom-report-dropdown .dropdown-item {
    color: white;
}

.el-date-editor.el-input, .el-date-editor.el-input__inner {
    width: 100%;
}

.el-input__inner {
    background: #474747;
    border: 1px solid #ccc;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
}

.time-select-item {
    font-weight: 500;
    color: #fff;
}

.time-select-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.time-select-item.selected:not(.disabled) {
    color: #555;
}

@media screen and (max-width: 625px) {
    .el-date-range-picker .el-picker-panel__body {
        min-width: unset !important;
    }

    .el-picker-panel {
        width: 100% !important;
    }

    .el-date-table th {
        font-size: 10px !important;
    }

    .el-date-range-picker__content .el-date-range-picker__header div {
        font-size: 11px !important;
    }
}
</style>
