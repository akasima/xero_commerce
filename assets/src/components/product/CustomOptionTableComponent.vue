<template>
  <div>
    <div class="form-group">
      <div class="dropdown">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            옵션 추가 <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-left" role="menu">
            <li v-for="(name, key) in types"><a href="#" @click.prevent="addCustomOption(key)">{{ name }}</a></li>
          </ul>
        </div>
      </div>
    </div>
    <table class="table table-striped">
      <thead>
      <tr>
        <th class="xe-form__label--requried">옵션타입</th>
        <th class="xe-form__label--requried">옵션명</th>
        <th class="xe-form__label--requried">옵션설정</th>
        <th>관리</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(option, key) in customOptionList">
        <input type="hidden" :name="`custom_options[${key}][id]`" :value="option.id" :disabled="!option.id" />
        <input type="hidden" :name="`custom_options[${key}][type]`" :value="option.type" />
        <td>{{ types[option.type] }}</td>
        <td><input type="text" :name="`custom_options[${key}][name]`" class="form-control" v-model="option.name" /></td>
        <td>
          <input type="hidden" :name="`custom_options[${key}][is_required]`" :value="option.is_required ? 1 : 0" />
          <label><input type="checkbox" v-model="option.is_required" />필수 여부</label>
          {{ option.settings }}
        </td>
        <td>
          <button type="button" class="btn btn-danger" @click="removeCustomOption(key)">삭제</button>
        </td>
      </tr>
      </tbody>
    </table>
    <input type="hidden" name="is_custom_option_changed" :value="isCustomOptionListChanged" />
  </div>
</template>

<script>

  export default {
    name: "CustomOptionTableComponent",
    props: [
      'customOptions', 'types'
    ],
    data() {
      return {
        isCustomOptionListChanged: false,
        customOptionList: this.customOptions
      }
    },
    watch: {
      // optionList가 변화했는지 확인
      customOptionList: {
        deep: true,
        handler(e) {
          this.isCustomOptionListChanged = true;
        }
      }
    },
    methods: {
      // 옵션 관련 함수
      addCustomOption(type) {
        this.customOptionList.push({
          type: type,
          name: '',
          sort_order: 0,
          is_required: false,
          settings: {},
        })
        console.log(this.types);
      },
      removeCustomOption(key) {
        this.customOptionList.splice(key, 1);
      },
    }
  }
</script>

<style scoped>

</style>
