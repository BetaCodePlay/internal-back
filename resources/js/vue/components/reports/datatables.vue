<template>
    <DataTable class="mt-3" :value="items" responsiveLayout="scroll">
        <Column
            v-for="col of columns"
            :field="col.field"
            :header="col.header"
            :key="col.field"
        ></Column>
    </DataTable>
</template>
<script>
export default {
    props: {
        items: {
            type: Array,
            default: [],
        },
        columns: {
            type: Array,
            default: [],
        },
    },
};
</script>
