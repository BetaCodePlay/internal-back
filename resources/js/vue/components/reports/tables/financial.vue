<template>
    <div>
        <components-reports-filters />
        <components-reports-datatables :items="products" :columns="columns" />
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            products: [],
            columns: [
                { field: "userId", header: "ID" },
                { field: "title", header: "Name" },
                { field: "completed", header: "Completed" },
            ],
        };
    },
    methods: {
        fetchProducts() {
            axios
                .get("https://jsonplaceholder.typicode.com/todos")
                .then((resp) => {
                    this.products = resp.data;
                });
        },
    },
    mounted() {
        this.fetchProducts();
    },
};
</script>
