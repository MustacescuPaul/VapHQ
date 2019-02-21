<!-- <template>
    <table class="table" >
        <thead>
            <tr>
        <th ><abbr title="Adresa" > {{adresa}} </abbr></th>
        <th><abbr title="Vapoint"> {{numeVapoint}}</abbr></th>
        <th><abbr title="Nume Client"><button class="button is-primary" @click="deschideIntrare"> {{numeClient}}</button></abbr></th>
        <th><abbr title="Telefon Client"> {{telefon}}</abbr></th>
        <th><abbr title="Email Client"> {{email}}</abbr></th>
        <th><abbr title="Data Intrare"> {{dataIntrare}}</abbr></th>
        <th><abbr title="Status"> {{stat}}</abbr></th>
        <th><abbr title="Primita"> <input type="checkbox" :checked="temp" @click="primitVapoint" :disabled="stat != 'Expediat @ Service Vapez'" class="checkbox"></abbr></th>
    </tr>
</thead>
    </table>
                <tr v-for="intrare in llist">
                    <td>{{numeVapoint}}</td>
                    <td>{{numeClient}}</td>
                    <td>{{adresa}}</td>
                    <td>{{telefon}}</td>
                    <td>{{email}}</td>
                    <td>{{dataIntrare}}</td>
                    <td>{{stat}}</td>
                    <td><i :id="intrare.id" @click="deschideIntrare" class="fas fa-chevron-circle-right"></i></td>
                </tr>
                
</template>
<script>
export default {
    props: ['id_intrare','adresa', 'numeVapoint', 'numeClient', 'telefon', 'email', 'dataIntrare', 'status'],
    data: function() {
        return {
            stat: this.status,
            temp: '',
        }
    },
    methods: {
        primitVapoint: function (event){
            this.$emit('primitVapoint',this.id_intrare);
            this.stat = 'Primit @ '+ this.numeVapoint;
        },
        deschideIntrare: function(event){
            this.$emit('deschideIntrare',this.id_intrare);
        },
    },
    beforeUpdate() {
      
    }
}

</script>
 -->