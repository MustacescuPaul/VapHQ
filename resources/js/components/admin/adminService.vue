<template>
    <div>
        <table v-show="show == 'intrari'" class="table is-centered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nume Vapoint</th>
                    <th>Nume client</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Primit</th>
                    <th>Expediat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="intrare in llist">
                    <td>{{intrare.id}}</td>
                    <td v-bind:class="{'is-danger': !intrare.remediat}">{{intrare.nume_vapoint}}</td>
                    <td>{{intrare.nume_client}}</td>
                    <td>{{intrare.data}}</td>
                    <td>{{intrare.status}}</td>
                    <td><input type="checkbox" :checked="temp" value="Primit" :id="intrare.id" @click="primitService" :disabled="intrare.status != 'Expediat @ ' + intrare.nume_vapoint" class="checkbox"></td>
                    <td><input type="checkbox" :checked="temp" value="Expediat" :id="intrare.id" @click="primitService" :disabled="intrare.status != 'Primit @ Service' || intrare.remediat == 0" class="checkbox"></td>
                    <td><i :id="intrare.id" :telefon="intrare.telefon_client" :adresa="intrare.adresa_client" :nume="intrare.nume_client" :email="intrare.email_client" :id_vapoint="intrare.id_vapoint" @click="getProduse" class="fas fa-chevron-circle-right"></i></td>
                </tr>
            </tbody>
        </table>
        <i v-show="show == 'produse'" class="fas fa-chevron-circle-left" @click="showIntrari"></i>
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
                    <td><textarea class="textarea" @blur="rezolvat" placeholder="Remediere defect...">{{product.remediere}}</textarea>
                        <p v-if="errors.text" class="help is-danger">{{errors.text}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="columns" v-if="show == 'produse'">
            <div class="column is-half">
                <div class="card">
                    <div class="card-content" style="justify-content: center;align-items: center;text-align: center;">
                        <div class="content  is-centered">
                           <p>{{nume_client}}</p>
                           <p>{{adresa_client}}</p>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <p class="card-footer-item">
                            <span>
                                Telefon: {{telefon_client}}
                            </span>
                        </p>
                        <p class="card-footer-item">
                            <span>
                                Email: {{email_client}}
                            </span>
                        </p>
                    </footer>
                </div>
            </div>
            <div class="column">
                <div class="card">
                    <div class="card-content"style="justify-content: center;align-items: center;text-align: center;">
                        <p>{{nume_vapoint}}</p>

                           
                    </div>
                    <footer class="card-footer">
                        <p class="card-footer-item">
                            <span>
                                Telefon: {{telefon_vapoint}}
                            </span>
                        </p>
                        <p class="card-footer-item">
                            <span>
                                Email: {{email_vapoint}}
                            </span>
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['llist'],
    data() {
        return {

            prods: [],
            show: 'intrari',
            temp: '',
            id_intrare: '',
            errors: [],
            email_client: '',
            adresa_client: '',
            telefon_client: '',
            email_vapoint: '',
            telefon_vapoint: '',
            nume_vapoint: '',
            nume_client: '',
            id_vapoint: '',
           
        }
    },
    methods: {
        getProduse: function(event) {
            this.id_intrare = event.currentTarget.id;
            this.email_client = event.currentTarget.getAttribute('email');
            this.adresa_client = event.currentTarget.getAttribute('adresa');
            this.telefon_client = event.currentTarget.getAttribute('telefon');
            this.nume_client = event.currentTarget.getAttribute('nume');
            this.id_vapoint = event.currentTarget.getAttribute('id_vapoint');
          
            axios.post('/admin/getVapoints', { id: this.id_vapoint}).then(response => {
                console.log(response.data);
                this.nume_vapoint = response.data.nume;
                this.telefon_vapoint = response.data.telefon;
                this.email_vapoint = response.data.email;

            });

            axios.post('/admin/getProduseIntrare', { id: this.id_intrare }).then(response => {

                this.prods = response.data;
                this.show = 'produse';
            });

        },
        showIntrari: function(event){
          
             axios.post('/admin/getService', { vap: this.id_vapoint }).then(response => {
               
                this.llist = response.data;
                this.show = 'intrari';

            });
        },
        primitService: function(event) {
            let id = event.currentTarget.id;
            let stat = event.currentTarget.value;
            axios.post('/garantii/primit_service', { id: id, stat: stat }).then(response => {
               
                this.llist = response.data;
            });
        },
        rezolvat: function(event) {
            let text = event.currentTarget.value;

            axios.post('/garantii/rezolvat', { id: this.id_intrare, text: text }).then(response => {
                this.errors = '';

            }).catch(error => {

                this.errors = error.response.data.errors;


            });;
        },
    }
}

</script>
