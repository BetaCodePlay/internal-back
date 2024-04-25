<template>
    <div>
        <Card class="mt-3">
            <template #content>
                <div class="row">
                    <div class="col-md-4">
                        <label>Filtrar por</label>
                        <Dropdown
                            v-model="value.selectedCity"
                            :options="cities"
                            class="form-control"
                            optionLabel="name"
                            @change="updateFilters"
                            placeholder="Select city"
                            :filter="true"
                            filterPlaceholder="Find Car"
                        />
                    </div>
                    <div class="col-md-4">
                        <label>Usuario</label>
                        <Dropdown
                            v-model="value.selectedCity"
                            :options="cities"
                            class="form-control"
                            optionLabel="name"
                            @change="updateFilters"
                            placeholder="Select city"
                            :filter="true"
                            filterPlaceholder="Find Car"
                        />
                    </div>
                    <div class="col-md-4">
                        <label>Fechas</label>
                        <Calendar
                            :locale="es"
                            v-model="value.daterange"
                            @input="updateFilters"
                            class="form-control"
                            selectionMode="range"
                        />
                    </div>
                </div>
            </template>
        </Card>

        <Card class="mt-5">
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
                        <div class="dropdown">
                            <button
                                class="btn"
                                type="button"
                                style="color: white"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Filtrar contenido
                                <i class="pi pi-angle-down ml-3"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Action</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Another action</a
                                    >
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Something else here</a
                                    >
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn"
                                type="button"
                                style="color: white"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Action</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Another action</a
                                    >
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Something else here</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>
<script>
export default {
    props: ["value", "title"],
    data() {
        return {
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
        };
    },
    methods: {
        updateFilters() {
            this.$emit("change", this.value);
        },
    },
};
</script>
<style>
.p-calendar .p-datepicker {
    min-width: unset;
}
</style>