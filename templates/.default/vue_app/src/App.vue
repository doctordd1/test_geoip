<template>
  <n-card
    title="Проверка IP"
    :segmented="{
      content: true,
      footer: 'soft'
    }"
  >

      <n-space vertical>
        <n-input v-model:value="ip" type="text" placeholder="Введите IP"/>
      </n-space>
    <template #footer v-if="result_text.length > 0">
      <n-alert title="Результат" type="success" v-if="result">
        Результат : {{ result_text }}
      </n-alert>
      <n-alert title="Ошибка" type="error" v-else>
        Ошибка : {{ result_text }}
      </n-alert>
    
    </template>
    <template #action>
      <n-button type="info"  @click="getIPinfo">
        Получить информацию
      </n-button>
    </template>
  </n-card>

</template>

<script>
/* global BX */
import { NInput, NCard, NButton, NAlert} from "naive-ui"
export default {
  name: 'App',
  data: () => {
    return {
        result_text:"",
        result:true,
        ip : ""
    }
  },
  methods: {
    async getIPinfo(){
      try {
        let res =  await BX.ajax.runComponentAction('custom:geoip','getInfoIP',{mode: 'class',data: { ip : this.ip}})
        this.result = true;
        this.result_text = res.data;
      } catch (error) {
        this.result = false;
        this.result_text = error.errors[0].message;
  
      }
    }
  },
  components: {
      NInput,
      NCard,
      NButton,
      NAlert
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
