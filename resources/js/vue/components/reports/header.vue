<template>
    <div class="row">
        <div class="col-lg-9 mb-2">
            <div class="row">
                <div class="col-lg-5 mt-2">
                    <Card class="card-parent" style="border-radius: 8px">
                        <template #content>
                            <h3 class="text-center">Balance total</h3>
                            <h1 class="text-center">
                                {{ parseFloat(window.userBalance).formatMoney()  }}
                            </h1>
                        </template>
                    </Card>
                </div>
                
                <div
                    class="col-4 col-lg-2 mt-2"
                    
                >
                    <Card class="card-transparent">
                        <template #content>
                            <span
                                class="text-center mt-2"
                                style="width: 100%; display: block"
                                >Depositado</span
                            >
                            <h5 class="text-center">
                                {{ parseFloat(window.userBalance).formatMoney() }}
                            </h5>
                        </template>
                    </Card>
                </div>
                <div class="col-4 col-lg-2 mt-2" style="
                        border-right: 0.5px solid #2d2d2d;
                        border-left: 0.5px solid #2d2d2d;
                    ">
                    <Card class="card-transparent">
                        <template #content>
                            <span
                                class="text-center mt-2"
                                style="width: 100%; display: block"
                                >Ganancia</span
                            >
                            <h5 class="text-center">
                                {{ parseFloat(window.userBalance).formatMoney() }}
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
                                {{ parseFloat(window.userBalance).formatMoney() }}
                            </h5>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            window
        }
    }
}
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
