<template>
    <div>
        <i class="fas fa-chevron-circle-left" @click="back"></i>
    <table class="table">
        <thead>
            <tr>
                <th>Nume</th>
                <th>Serial</th>
                <th>Defect</th>
                <th>Stare</th>
            </tr>
        </thead>
        <tbody>
            {{this.message}}
            <tr v-for="produs in produse_intrare">
                <td>Nume: {{produs.nume}}</td>
                <td>Serial: {{produs.cod}}</td>
                <td>Defect: {{produs.defect}}</td>
                <td>Stare: {{produs.stare}}</td>
            </tr>
        </tbody>
    </table>
    
    </div>
</template>
<script>
export default {
    props: ['produse_intrare'],
    data: function() {
        return {
            message: '',
        }
    },
    methods: {
        back: function(event){
            this.$emit('Back');

        },
    },
    beforeUpdate() {
        if(this.produse_intrare.length == 0)
        this.message ="Nu au fost gasite produse pentru aceasta intrare!";
    }
}

</script>
