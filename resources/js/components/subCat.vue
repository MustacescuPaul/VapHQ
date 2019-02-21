<!-- <template>

<div class="side-menu" style="overflow-y: auto;">
  <aside class="menu">
    <ul class="menu-list">
  
       <li v-for="(men,index) in menu[1]">
        <button class="button is-outlined is-fullwidth" :id_cat="index" @click="showChild">{{ men }}</button> 
           <li  v-for="(smen,sindex) in showChild">
             <button class="button is-outlined is-fullwidth" :id_cat="sindex" @click="showChild">{{ smen }}</button>
            </li>
    </li>
      
         
         
    </ul>
  </aside>
</div>

</template>

<script>
    export default {
        props: ['menu'],
        data:function() {
                 return  {
                    submenu: [],
                }
        },
        mounted() {
           console.log(this.menu)
        },
        methods:{
            showChild: function (event)
            {/*
                let id_cat =event.target.getAttribute('id_cat')
                let but = event.target.parentElement
                var ul = document.createElement('ul');
                for (var i in this.menu[id_cat]) {
                  console.log(i)
                    var li = document.createElement('li');
                li.innerHTML = '<button  class="button is-outlined is-fullwidth" :id_cat="'+i+'" @click="showChild">'+this.menu[id_cat][i]+'</button>'
                ul.appendChild(li)
               }
                but.appendChild(ul)*/
                
            }
        }
    }//de vcontinuat https://www.youtube.com/watch?v=uDlPoH2Wpq8
</script>
 -->