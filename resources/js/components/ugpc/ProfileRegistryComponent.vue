<template>
    <div style="display: flex;">
      <VendorsBlock :type="type" :vendors="vendors" style="width:20%; margin-right: 20px; background: white; padding: 15px;"/>
      <ProfilesBlock v-if="profiles" :type="type" :profiles="profiles" :vendors="vendors" style="width:80%; background: white; padding: 15px;"/>
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex'
  import VendorsBlock from "./blocks/VendorsBlock.vue";
  import ProfilesBlock from "./blocks/ProfilesBlock.vue";
  export default {
      props: {
          vendors: Array,
          user: Object,
          type: String,
      }, 
      components: {
        VendorsBlock,
        ProfilesBlock,
      },
      data() {
          return {

          };
      },
      created() {
          this.setTypeRegistry();
      },
      methods: {
        async setTypeRegistry() {
            await this.$store.dispatch('setTypeRegistry',this.type);
        }
      },

      computed: {
          ...mapGetters({
              profiles: 'getProfiles'
          }),
      }
  };
  </script>