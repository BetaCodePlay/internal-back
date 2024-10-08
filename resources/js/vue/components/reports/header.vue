<template>
    <div
        class="row"
        v-loading="loading"
        element-loading-text="Cargando..."
        element-loading-spinner="el-icon-loading"
    >
        <div class="col-lg-9 mb-2">
            <div class="row">
                <div class="col-lg-5 mt-2">
                    <Card class="card-parent" style="border-radius: 8px">
                        <template #content>
                            <h3 class="text-center">Balance total</h3>
                            <h1 class="text-center">
                                {{ window.userBalance }}
                            </h1>
                        </template>
                    </Card>
                </div>

                <div class="col-4 col-lg-2 mt-2">
                    <Card class="card-transparent">
                        <template #content>
                            <span
                                class="text-center mt-2"
                                style="width: 100%; display: block"
                                >Depositado</span
                            >
                            <h5 class="text-center">
                                {{ deposits }}
                            </h5>
                        </template>
                    </Card>
                </div>
                <div class="col-4 col-lg-2 mt-2">
                    <Card class="card-transparent">
                        <template #content>
                            <span
                                class="text-center mt-2"
                                style="width: 100%; display: block"
                                >Ganancia</span
                            >
                            <h5 class="text-center">
                                {{ profit }}
                            </h5>
                        </template>
                    </Card>
                </div>
                <div class="col-4 col-lg-2 mt-2">
                    <Card class="card-transparent">
                        <template #content>
                            <span
                                class="text-center mt-2"
                                style="width: 100%; display: block"
                                >Retirado</span
                            >
                            <h5 class="text-center">
                                {{ withdrawals }}
                            </h5>
                        </template>
                    </Card>
                </div>
                <div class="loading-style" v-if="loading"></div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            window,
            loading: false,
            deposits: 0,
            withdrawals: 0,
            profit: 0,
        };
    },
    methods: {
        async loadChildDailyMovements() {
            this.loading = true;
            try {
                const { data } = await axios.get(
                    "/api-transactions/daily-movements-of-children"
                );
                const { deposits, withdrawals, profit } = data;

                this.deposits = deposits;
                this.withdrawals = withdrawals;
                this.profit = profit;
            } catch (error) {
                console.error(error);
            } finally {
                setTimeout(() => {
                    this.loading = false;
                }, 500);
            }
        },
    },
    mounted() {
        this.loadChildDailyMovements();
    },
};
</script>
<style>
.cuadrado {
    width: 100%;
    height: 100px;
    background-color: magenta;
}

.card-transparent {
    padding: 5px;
    background: transparent;
    box-shadow: unset;
}

@media screen and (max-width: 992px) {
    .card-parent {
        background-color: transparent;
        box-shadow: unset;
    }
}
</style>
