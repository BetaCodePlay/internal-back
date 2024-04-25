window.Vue = require("vue");
import PrimeVue from "primevue/config";
import Calendar from "primevue/calendar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import Card from "primevue/card";
import Dropdown from "primevue/dropdown";
import OverlayPanel from "primevue/overlaypanel";
import Row from "primevue/row";

import "primevue/resources/themes/bootstrap4-dark-purple/theme.css";
import "primevue/resources/primevue.min.css";
import "./vue/assets/custom.css";
//import "primeicons/primeicons.css";

Vue.use(PrimeVue);
Vue.component("Calendar", Calendar);
Vue.component("DataTable", DataTable);
Vue.component("TabView", TabView);
Vue.component("TabPanel", TabPanel);
Vue.component("Card", Card);
Vue.component("Dropdown", Dropdown);
Vue.component("OverlayPanel", OverlayPanel);
Vue.component("Column", Column);
Vue.component("ColumnGroup", ColumnGroup);
Vue.component("Row", Row);

// Función para importar recursivamente todos los componentes
function importComponents(context, path = "") {
    context.keys().forEach((key) => {
        const component = context(key).default;
        const componentName = key
            .replace(/^\.\//, "") // Elimina './' del inicio del nombre del archivo
            .replace(/\.\w+$/, "") // Elimina la extensión del archivo
            .replace(/\//g, "-"); // Reemplaza '/' con '-'
        const fullComponentName = `${path}${componentName}`;
        Vue.component(fullComponentName, component);
    });
}

// Importa recursivamente todos los componentes dentro de la carpeta 'components'
const files = require.context("./vue", true, /\.vue$/);
importComponents(files, "");

const app = new Vue({
    el: "#app",
});
