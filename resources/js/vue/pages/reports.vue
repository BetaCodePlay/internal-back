<template>
    <div>
        <div class="wrapper-title g-pb-30">Movimiento diario</div>
        <div class="page-roles">
            <components-reports-header class="mb-5"/>
            <div class="wrapper-title g-pb-30">Actividad general</div>
            <TabView>
                <TabPanel header="Reporte Financiero">
                    <components-reports-tables-financial/>
                </TabPanel>
                <TabPanel header="Reporte de transacciones">
                    <components-reports-tables-transactions/>
                </TabPanel>
            </TabView>
        </div>
    </div>
</template>
<script>
export default {
    name: "reports",
};
</script>
