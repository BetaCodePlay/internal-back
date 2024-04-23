<template>
    <div>
        <div class="wrapper-title g-pb-30">Actividad General</div>
        <div class="page-roles">
            <!-- <components-reports-header /> -->
            <TabView>
                <TabPanel header="Reporte Financiero">
                    <components-reports-tables-financial />
                </TabPanel>
                <TabPanel header="Reporte de transacciones">
                    <components-reports-tables-transactions />
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
