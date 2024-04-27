<template>
    <div>
        <Card>
            <template #content>
                <div class="row">
                    <div class="col-md-8 pt-2">
                        <h4>{{ title }}</h4>
                    </div>
                    <div class="col-md-2">
                        <i
                            class="pi pi-search"
                            style="position: absolute; top: 12px; left: 25px"
                        ></i>
                        <input
                            type="text"
                            placeholder="Buscar"
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
                    <div class="col-md-2 pt-1">
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
                                                        <label>Zona Horaria</label>
                                                        <Dropdown
                                                            v-model="
                                                                value.selectedTimezone
                                                            "
                                                            :options="timezones"
                                                            class="form-control"
                                                            optionLabel="zone"
                                                            optionValue="zone"
                                                            dataKey="zone"
                                                            :showClear="true"
                                                            @change="
                                                                updateFilters
                                                            "
                                                            placeholder="Selecciona zona horaria"
                                                            :filter="true"
                                                            filterPlaceholder="Busacr zona horaria"
                                                        />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label>Usuario</label>
                                                        <Dropdown
                                                            v-model="
                                                                value.selectUser
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
                                            <a class="dropdown-item" href="#"
                                                >Export Excel</a
                                            >
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                >Export PDF</a
                                            >
                                        </li>
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
    props: ["value", "title"],
    data() {
        return {
            childs: [],
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
            cities: [
                { name: "New York", code: "NY" },
                { name: "Rome", code: "RM" },
                { name: "London", code: "LDN" },
                { name: "Istanbul", code: "IST" },
                { name: "Paris", code: "PRS" },
            ],
            timezones: [
                {
                    zone: "Pacific/Midway",
                    utc: "(GMT-11:00)",
                    name: "Midway Island",
                },
                {
                    zone: "US/Samoa",
                    utc: "(GMT-11:00)",
                    name: "Samoa",
                },
                {
                    zone: "US/Hawaii",
                    utc: "(GMT-10:00)",
                    name: "Hawaii",
                },
                {
                    zone: "US/Alaska",
                    utc: "(GMT-09:00)",
                    name: "Alaska",
                },
                {
                    zone: "US/Pacific",
                    utc: "(GMT-08:00)",
                    name: "Pacific Time (US &amp; Canada)",
                },
                {
                    zone: "America/Tijuana",
                    utc: "(GMT-08:00)",
                    name: "Tijuana",
                },
                {
                    zone: "US/Arizona",
                    utc: "(GMT-07:00)",
                    name: "Arizona",
                },
                {
                    zone: "US/Mountain",
                    utc: "(GMT-07:00)",
                    name: "Mountain Time (US &amp; Canada)",
                },
                {
                    zone: "America/Chihuahua",
                    utc: "(GMT-07:00)",
                    name: "Chihuahua",
                },
                {
                    zone: "America/Mazatlan",
                    utc: "(GMT-07:00)",
                    name: "Mazatlan",
                },
                {
                    zone: "America/Mexico_City",
                    utc: "(GMT-06:00)",
                    name: "Mexico City",
                },
                {
                    zone: "America/Monterrey",
                    utc: "(GMT-06:00)",
                    name: "Monterrey",
                },
                {
                    zone: "Canada/Saskatchewan",
                    utc: "(GMT-06:00)",
                    name: "Saskatchewan",
                },
                {
                    zone: "US/Central",
                    utc: "(GMT-06:00)",
                    name: "Central Time (US &amp; Canada)",
                },
                {
                    zone: "US/Eastern",
                    utc: "(GMT-05:00)",
                    name: "Eastern Time (US &amp; Canada)",
                },
                {
                    zone: "US/East-Indiana",
                    utc: "(GMT-05:00)",
                    name: "Indiana (East)",
                },
                {
                    zone: "America/Bogota",
                    utc: "(GMT-05:00)",
                    name: "Bogota",
                },
                {
                    zone: "America/Lima",
                    utc: "(GMT-05:00)",
                    name: "Lima",
                },
                {
                    zone: "America/Caracas",
                    utc: "(GMT-04:30)",
                    name: "Caracas",
                },
                {
                    zone: "Canada/Atlantic",
                    utc: "(GMT-04:00)",
                    name: "Atlantic Time (Canada)",
                },
                {
                    zone: "America/La_Paz",
                    utc: "(GMT-04:00)",
                    name: "La_Paz",
                },
                {
                    zone: "America/Santiago",
                    utc: "(GMT-04:00)",
                    name: "Santiago",
                },
                {
                    zone: "Canada/Newfoundland",
                    utc: "(GMT-03:30)",
                    name: "Newfoundland",
                },
                {
                    zone: "America/Buenos_Aires",
                    utc: "(GMT-03:00)",
                    name: "Buenos Aires",
                },
                {
                    zone: "Greenland",
                    utc: "(GMT-03:00)",
                    name: "Greenland",
                },
                {
                    zone: "Atlantic/Stanley",
                    utc: "(GMT-02:00)",
                    name: "Stanley",
                },
                {
                    zone: "Atlantic/Azores",
                    utc: "(GMT-01:00)",
                    name: "Azores",
                },
                {
                    zone: "Atlantic/Cape_Verde",
                    utc: "(GMT-01:00)",
                    name: "Cape Verde Is.",
                },
                {
                    zone: "Africa/Casablanca",
                    utc: "(GMT)",
                    name: "Casablanca",
                },
                {
                    zone: "Europe/Dublin",
                    utc: "(GMT)",
                    name: "Dublin",
                },
                {
                    zone: "Europe/Lisbon",
                    utc: "(GMT)",
                    name: "Libson",
                },
                {
                    zone: "Europe/London",
                    utc: "(GMT)",
                    name: "London",
                },
                {
                    zone: "Africa/Monrovia",
                    utc: "(GMT)",
                    name: "Monrovia",
                },
                {
                    zone: "Europe/Amsterdam",
                    utc: "(UTC+01:00)",
                    name: "Amsterdam",
                },
                {
                    zone: "Europe/Belgrade",
                    utc: "(UTC+01:00)",
                    name: "Belgrade",
                },
                {
                    zone: "Europe/Berlin",
                    utc: "(UTC+01:00)",
                    name: "Berlin",
                },
                {
                    zone: "Europe/Bratislava",
                    utc: "(UTC+01:00)",
                    name: "Bratislava",
                },
                {
                    zone: "Europe/Brussels",
                    utc: "(UTC+01:00)",
                    name: "Brussels",
                },
                {
                    zone: "Europe/Budapest",
                    utc: "(UTC+01:00)",
                    name: "Budapest",
                },
                {
                    zone: "Europe/Copenhagen",
                    utc: "(UTC+01:00)",
                    name: "Copenhagen",
                },
                {
                    zone: "Europe/Ljubljana",
                    utc: "(UTC+01:00)",
                    name: "Ljubljana",
                },
                {
                    zone: "Europe/Madrid",
                    utc: "(UTC+01:00)",
                    name: "Madrid",
                },
                {
                    zone: "Europe/Paris",
                    utc: "(UTC+01:00)",
                    name: "Paris",
                },
                {
                    zone: "Europe/Prague",
                    utc: "(UTC+01:00)",
                    name: "Prague",
                },
                {
                    zone: "Europe/Rome",
                    utc: "(UTC+01:00)",
                    name: "Rome",
                },
                {
                    zone: "Europe/Sarajevo",
                    utc: "(UTC+01:00)",
                    name: "Sarajevo",
                },
                {
                    zone: "Europe/Skopje",
                    utc: "(UTC+01:00)",
                    name: "Skopje",
                },
                {
                    zone: "Europe/Stockholm",
                    utc: "(UTC+01:00)",
                    name: "Stockholm",
                },
                {
                    zone: "Europe/Vienna",
                    utc: "(UTC+01:00)",
                    name: "Vienna",
                },
                {
                    zone: "Europe/Warsaw",
                    utc: "(UTC+01:00)",
                    name: "Warsaw",
                },
                {
                    zone: "Europe/Zagreb",
                    utc: "(UTC+01:00)",
                    name: "Zagreb",
                },
                {
                    zone: "Europe/Athens",
                    utc: "(UTC+02:00)",
                    name: "Athens",
                },
                {
                    zone: "Europe/Bucharest",
                    utc: "(UTC+02:00)",
                    name: "Bucharest",
                },
                {
                    zone: "Africa/Cairo",
                    utc: "(UTC+02:00)",
                    name: "Cairo",
                },
                {
                    zone: "Africa/Harare",
                    utc: "(UTC+02:00)",
                    name: "Harere",
                },
                {
                    zone: "Europe/Helsinki",
                    utc: "(UTC+02:00)",
                    name: "Helsinki",
                },
                {
                    zone: "Europe/Istanbul",
                    utc: "(UTC+02:00)",
                    name: "Istanbul",
                },
                {
                    zone: "Asia/Jerusalem",
                    utc: "(UTC+02:00)",
                    name: "Jerusalem",
                },
                {
                    zone: "Europe/Kiev",
                    utc: "(UTC+02:00)",
                    name: "Kiev",
                },
                {
                    zone: "Europe/Minsk",
                    utc: "(UTC+02:00)",
                    name: "Minsk",
                },
                {
                    zone: "Europe/Riga",
                    utc: "(UTC+02:00)",
                    name: "Riga",
                },
                {
                    zone: "Europe/Sofia",
                    utc: "(UTC+02:00)",
                    name: "Sofia",
                },
                {
                    zone: "Europe/Tallinn",
                    utc: "(UTC+02:00)",
                    name: "Tallinn",
                },
                {
                    zone: "Europe/Vilnius",
                    utc: "(UTC+02:00)",
                    name: "Vilnius",
                },
                {
                    zone: "Asia/Baghdad",
                    utc: "(UTC+03:00)",
                    name: "Baghdad",
                },
                {
                    zone: "Asia/Kuwait",
                    utc: "(UTC+03:00)",
                    name: "Kuwait",
                },
                {
                    zone: "Africa/Nairobi",
                    utc: "(UTC+03:00)",
                    name: "Nairobi",
                },
                {
                    zone: "Asia/Riyadh",
                    utc: "(UTC+03:00)",
                    name: "Riyadh",
                },
                {
                    zone: "Asia/Tehran",
                    utc: "(UTC+03:30)",
                    name: "Tehran",
                },
                {
                    zone: "Europe/Moscow",
                    utc: "(UTC+04:00)",
                    name: "Moscow",
                },
                {
                    zone: "Asia/Baku",
                    utc: "(UTC+04:00)",
                    name: "Baku",
                },
                {
                    zone: "Europe/Volgograd",
                    utc: "(UTC+04:00)",
                    name: "Volgograd",
                },
                {
                    zone: "Asia/Muscat",
                    utc: "(UTC+04:00)",
                    name: "Muscat",
                },
                {
                    zone: "Asia/Tbilisi",
                    utc: "(UTC+04:00)",
                    name: "Tbilisi",
                },
                {
                    zone: "Asia/Yerevan",
                    utc: "(UTC+04:00)",
                    name: "Yerevan",
                },
                {
                    zone: "Asia/Kabul",
                    utc: "(UTC+04:30)",
                    name: "Kabul",
                },
                {
                    zone: "Asia/Karachi",
                    utc: "(UTC+05:00)",
                    name: "Karachi",
                },
                {
                    zone: "Asia/Tashkent",
                    utc: "(UTC+05:00)",
                    name: "Tashkent",
                },
                {
                    zone: "Asia/Kolkata",
                    utc: "(UTC+05:30)",
                    name: "Kolkata",
                },
                {
                    zone: "Asia/Kathmandu",
                    utc: "(UTC+05:45)",
                    name: "Kathmandu",
                },
                {
                    zone: "Asia/Yekaterinburg",
                    utc: "(UTC+06:00)",
                    name: "Yekaterinburg",
                },
                {
                    zone: "Asia/Almaty",
                    utc: "(UTC+06:00)",
                    name: "Almaty",
                },
                {
                    zone: "Asia/Dhaka",
                    utc: "(UTC+06:00)",
                    name: "Dhaka",
                },
                {
                    zone: "Asia/Novosibirsk",
                    utc: "(UTC+07:00)",
                    name: "Novosibirsk",
                },
                {
                    zone: "Asia/Bangkok",
                    utc: "(UTC+07:00)",
                    name: "Bangkok",
                },
                {
                    zone: "Asia/Jakarta",
                    utc: "(UTC+07:00)",
                    name: "Jakarta",
                },
                {
                    zone: "Asia/Krasnoyarsk",
                    utc: "(UTC+08:00)",
                    name: "Krasnoyarsk",
                },
                {
                    zone: "Asia/Chongqing",
                    utc: "(UTC+08:00)",
                    name: "Chongqing",
                },
                {
                    zone: "Asia/Hong_Kong",
                    utc: "(UTC+08:00)",
                    name: "Hong Kong",
                },
                {
                    zone: "Asia/Kuala_Lumpur",
                    utc: "(UTC+08:00)",
                    name: "Kuala Lumpur",
                },
                {
                    zone: "Australia/Perth",
                    utc: "(UTC+08:00)",
                    name: "Perth",
                },
                {
                    zone: "Asia/Singapore",
                    utc: "(UTC+08:00)",
                    name: "Singapore",
                },
                {
                    zone: "Asia/Taipei",
                    utc: "(UTC+08:00)",
                    name: "Taipei",
                },
                {
                    zone: "Asia/Ulaanbaatar",
                    utc: "(UTC+08:00)",
                    name: "Ulaan Bataar",
                },
                {
                    zone: "Asia/Urumqi",
                    utc: "(UTC+08:00)",
                    name: "Urumqi",
                },
                {
                    zone: "Asia/Irkutsk",
                    utc: "(UTC+09:00)",
                    name: "Irkutsk",
                },
                {
                    zone: "Asia/Seoul",
                    utc: "(UTC+09:00)",
                    name: "Seoul",
                },
                {
                    zone: "Asia/Tokyo",
                    utc: "(UTC+09:00)",
                    name: "Tokyo",
                },
                {
                    zone: "Australia/Adelaide",
                    utc: "(UTC+09:30)",
                    name: "Adelaide",
                },
                {
                    zone: "Australia/Darwin",
                    utc: "(UTC+09:30)",
                    name: "Darwin",
                },
                {
                    zone: "Asia/Yakutsk",
                    utc: "(UTC+10:00)",
                    name: "Yakutsk",
                },
                {
                    zone: "Australia/Brisbane",
                    utc: "(UTC+10:00)",
                    name: "Brisbane",
                },
                {
                    zone: "Australia/Canberra",
                    utc: "(UTC+10:00)",
                    name: "Canberra",
                },
                {
                    zone: "Pacific/Guam",
                    utc: "(UTC+10:00)",
                    name: "Guam",
                },
                {
                    zone: "Australia/Hobart",
                    utc: "(UTC+10:00)",
                    name: "Hobart",
                },
                {
                    zone: "Australia/Melbourne",
                    utc: "(UTC+10:00)",
                    name: "Melbourne",
                },
                {
                    zone: "Pacific/Port_Moresby",
                    utc: "(UTC+10:00)",
                    name: "Port Moresby",
                },
                {
                    zone: "Australia/Sydney",
                    utc: "(UTC+10:00)",
                    name: "Sydney",
                },
                {
                    zone: "Asia/Vladivostok",
                    utc: "(UTC+11:00)",
                    name: "Vladivostok",
                },
                {
                    zone: "Asia/Magadan",
                    utc: "(UTC+12:00)",
                    name: "Magadan",
                },
                {
                    zone: "Pacific/Auckland",
                    utc: "(UTC+12:00)",
                    name: "Auckland",
                },
                {
                    zone: "Pacific/Fiji",
                    utc: "(UTC+12:00)",
                    name: "Fiji",
                },
            ],
        };
    },
    methods: {
        getChilds() {
            axios.get("/agents/reports/get-childrens").then((resp) => {
                this.childs = resp.data.data;
            });
        },
        updateFilters() {
            this.$emit("change", this.value);
        },
    },
    mounted() {
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
