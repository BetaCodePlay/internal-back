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
                                    >
                                        Filtrar contenido
                                        <i class="pi pi-angle-down ml-3"></i>
                                    </button>
                                    <form
                                        class="dropdown-menu dropdown-menu-right custom-report-dropdown"
                                        style="min-width: 400px"
                                    >
                                        <Card style="margin-top: 0px">
                                            <template #content>
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label>Fechas</label>
                                                        <Calendar
                                                            :locale="es"
                                                            v-model="
                                                                value.daterange
                                                            "
                                                            @input="
                                                                updateFilters
                                                            "
                                                            class="form-control"
                                                            selectionMode="range"
                                                        />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label
                                                            >Zona Horaria</label
                                                        >
                                                        <Dropdown
                                                            v-model="
                                                                value.selectedTimezone
                                                            "
                                                            :options="timezones"
                                                            class="form-control"
                                                            optionLabel="timezone"
                                                            optionValue="timezone"
                                                            dataKey="timezone"
                                                            :showClear="true"
                                                            @change="
                                                                updateFilters
                                                            "
                                                            placeholder="Selecciona zona horaria"
                                                            :filter="true"
                                                            filterPlaceholder="Buscar zona horaria"
                                                        />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label>Proveedor</label>
                                                        <Dropdown
                                                            v-model="
                                                                value.selectedProvider
                                                            "
                                                            :options="providers"
                                                            class="form-control"
                                                            optionLabel="provider"
                                                            optionValue="provider"
                                                            dataKey="provider"
                                                            :showClear="true"
                                                            @change="
                                                                updateFilters
                                                            "
                                                            placeholder="Seleccionar proveedor"
                                                            :filter="true"
                                                            filterPlaceholder="Buscar Proveedor"
                                                        />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label>Usuario</label>
                                                        <Dropdown
                                                            v-model="
                                                                value.selectedUser
                                                            "
                                                            :options="childs"
                                                            class="form-control"
                                                            optionLabel="username"
                                                            optionValue="id"
                                                            dataKey="id"
                                                            :showClear="true"
                                                            @change="
                                                                updateFilters
                                                            "
                                                            placeholder="Seleccionar usuario"
                                                            :filter="true"
                                                            filterPlaceholder="Buscar usuario"
                                                        />
                                                    </div>
                                                </div>
                                            </template>
                                        </Card>
                                    </form>
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
                                            <a @click="exportData('excel')" class="dropdown-item" href="javascript:void(0)"
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
                query:"",
                daterange: [],
                selectedUser: "",
                selectedTimezone: "",
                selectedProvider: "",
            },
        },
        title: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            childs: [],
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
</style>
