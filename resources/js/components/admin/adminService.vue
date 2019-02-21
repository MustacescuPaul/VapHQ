<template>
    <div class="table-container">
        <table v-show="show == 'intrari'" class="table is-centered">
            <thead>
                <tr>
                    <th>Nume Vapoint</th>
                    <th>Nume client</th>
                    <th>Adresa</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="intrare in llist">
                    <td>{{intrare.nume_vapoint}}</td>
                    <td>{{intrare.nume_client}}</td>
                    <td>{{intrare.adresa_client}}</td>
                    <td>{{intrare.telefon_client}}</td>
                    <td>{{intrare.email_client}}</td>
                    <td>{{intrare.data}}</td>
                    <td>{{intrare.status}}</td>
                    <td><i :id="intrare.id" @click="getProduse" class="fas fa-chevron-circle-right"></i></td>
                </tr>
            </tbody>
        </table>
        <i v-show="show == 'produse'" class="fas fa-chevron-circle-left" @click="show = 'intrari'"></i>
        <table v-show="show == 'produse'" class="table is-centered">
            <thead>
                <tr>
                    <th>Garantie</th>
                    <th>ID Produs</th>
                    <th>Nume</th>
                    <th>Garantie</th>
                    <th>Cod</th>
                    <th>Defect</th>
                    <th>Stare</th>
                    <th>Remediere defect</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in prods">
                    <td>{{product.id_garantie}}</td>
                    <td>{{product.id_prod}}</td>
                    <td>{{product.nume}}</td>
                    <td>{{product.garantie}}</td>
                    <td>{{product.cod}}</td>
                    <td>{{product.defect}}</td>
                    <td>{{product.stare}}</td>
                    <td><textarea class="textarea" placeholder="e.g. Hello world"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['llist'],
    data() {
        return {
          
            prods: [],
            show: 'intrari',

        }
    },
    methods: {
        getProduse: function(event) {
            let id = event.currentTarget.id;
            axios.post('/admin/getProduseIntrare', { id: id }).then(response => {

                this.prods = response.data;
                this.show = 'produse';
            });
        }
    }
}

</script>
