<template>
    <div>
        <Card class="mt-3">
            <template #content>
                <div class="row">
                    <div class="col-md-4">
                        <label>Filtrar por</label>
                        <Dropdown
                            v-model="selectedCity"
                            :options="cities"
                            class="form-control"
                            optionLabel="name"
                            placeholder="Select city"
                            :filter="true"
                            filterPlaceholder="Find Car"
                        />
                    </div>
                    <div class="col-md-4">
                        <label>Usuario</label>
                        <Dropdown
                            v-model="selectedCity"
                            :options="cities"
                            class="form-control"
                            optionLabel="name"
                            placeholder="Select city"
                            :filter="true"
                            filterPlaceholder="Find Car"
                        />
                    </div>
                    <div class="col-md-4">
                        <label>Fechas</label>
                        <Calendar
                            v-model="daterange"
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
                        <h4>Transacciones</h4>
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
                    <div class="col-md-2  pt-1">
                        <a
                            href="javascript:void(0)"
                            style="color: white"
                            @click="toggleFilters"
                            >Filtrar contenido
                            <i class="pi pi-angle-down ml-3"></i
                        ></a>
                        <span
                            @click="toggleExports"
                            class="btn ml-3"
                            style="color: white"
                            ><i class="fa-solid fa-ellipsis-vertical"></i
                        ></span>
                    </div>
                </div>
            </template>
        </Card>
        <OverlayPanel ref="filters" :showCloseIcon="true">
            <img
                width="400px"
                src="https://cdn.motor1.com/images/mgl/9mZpXv/s1/2020-2022-ford-mustang-shelby-gt500kr.webp"
                alt="Nature Image"
        /></OverlayPanel>
        <OverlayPanel ref="exports" :showCloseIcon="true">
            <img
                width="400px"
                src="https://cdn.motor1.com/images/mgl/9mZpXv/s1/2020-2022-ford-mustang-shelby-gt500kr.webp"
                alt="Nature Image"
        /></OverlayPanel>
    </div>
</template>
<script>
export default {
    data() {
        return {
            daterange: "",
            selectedCity: null,
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
        toggleFilters(event) {
            this.$refs.filters.toggle(event);
        },
        toggleExports(event) {
            this.$refs.exports.toggle(event);
        },
    },
};
</script>
