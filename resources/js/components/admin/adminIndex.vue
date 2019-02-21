<template>
    <div class="container is-fluid">
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">
                        <i class="fa fa-cloud">VapeHQ</i>
                    </h1>
                    <h2 class="subtitle">
                        Backoffice Vapez
                    </h2>
                </div>
            </div>
        </section>
        <div class="columns">
            <!-- Aside nav drawer -->
            <div class="column is-2 ">
                <admin-sidebar @getService="getService" @getAccounts="getAccounts" @switchContent="show = $event"></admin-sidebar>
            </div>
            <section class="column" style="overflow-x: auto;">
                <div v-if="show == 'service'" class="select">
                    <select v-model="vapoint" @change="getService">
                        <option v-for="vap in vapoints" :value="vap.nume">{{vap.nume}}</option>
                        <option value="">Toate</option>
                    </select>
                </div>
                <admin-content v-if="show == 'dashboard'" style="margin-top:10px;"></admin-content>
                <admin-accounts @switchContent="show = $event" @reg="getAccounts" @RegisterUser="RegisterUser($event)" v-if="show == 'creeare conturi'" style="margin-top:10px;"></admin-accounts>
                <accounts-settings :list="list" v-if="show == 'administrare conturi'"></accounts-settings>
                <admin-service v-if="show == 'service'" :llist="service"></admin-service>
            </section>
        </div>
    </div>
</template>
<script>
export default {
    props: [],
    data: function() {
        return {
            show: 'dashboard',
            list: [],
            service: [],
            vapoints: [],
            vapoint: 'Paul',
        }
    },
    methods: {
        getAccounts: function() {
            axios.post('/admin/accounts', {}).then(response => {

                this.list = response.data;
                this.show = "administrare conturi";
            });
        },
        getService: function() {
            axios.post('/admin/getService', { vap: this.vapoint }).then(response => {
                console.log(response.data);
                this.service = response.data;
                this.show = "service";
                console.log(this.service);

            });
            axios.post('/admin/getVapoints', {}).then(response => {

                this.vapoints = response.data;

            });
        },
    },
    mounted() {

    }
}

</script>
