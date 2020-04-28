<template>
  <div>
    <div class="form-group">
      <label class="control-label col-sm-2 xe-form__label--requried">옵션 타입</label>
      <div class="col-sm-10">
        <label><input type="radio" name="option_type" v-model="optionType" value="0" @click.prevent="confirmOptionTypeChange" />조합 일체선택형</label>
        <label><input type="radio" name="option_type" v-model="optionType" value="1" @click.prevent="confirmOptionTypeChange" />조합 분리선택형</label>
        <label><input type="radio" name="option_type" v-model="optionType" value="2" @click.prevent="confirmOptionTypeChange" />단독형</label>
      </div>
    </div>
    <div class="form-group">
      <a href="#" class="btn btn-sm btn-primary" @click.prevent="addOption"><i class="xi-plus"></i> 옵션 추가</a>
    </div>
    <table class="table table-striped">
      <thead>
      <tr>
        <th class="xe-form__label--requried">옵션명</th>
        <th class="xe-form__label--requried">옵션값</th>
        <th>관리</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(option, key) in optionList">
        <input type="hidden" :name="`options[${key}][id]`" :value="option.id" :disabled="!option.id" />
        <td v-show="!tmpEditStates[key]">{{ option.name }}</td>
        <td v-show="!tmpEditStates[key]">{{ option.values }}</td>

        <td v-show="tmpEditStates[key]"><input type="text" :name="`options[${key}][name]`" class="form-control" v-model="option.name" /></td>
        <td v-show="tmpEditStates[key]">
          <vue-tags-input
            v-model="tmpTexts[key]"
            :tags="tmpTags[key]"
            @tags-changed="newTags => option.values = newTags.map(obj => obj.text)"
            placeholder="옵션에 사용될 값을 입력하세요"></vue-tags-input>
          <input v-for="(value, i) in option.values" type="hidden" :name="`options[${key}][values][${i}]`" :value="value" />
        </td>
        <td>
          <button v-show="!tmpEditStates[key]" type="button" class="btn btn-default" @click="editOption(key)">수정</button>
          <button v-show="tmpEditStates[key]" type="button" class="btn btn-primary" @click="saveOption(key)">저장</button>
          <button type="button" class="btn btn-danger" @click="popOption(key)">삭제</button>
        </td>
      </tr>
      </tbody>
    </table>
    <input type="hidden" name="is_option_changed" :value="isOptionListChanged" />
    <div class="table-responsive">
      <div class="form-group">
        <a href="#add" class="btn btn-sm btn-primary" @click.prevent="confirmAddItemsByOption"><i class="xi-arrow-down"></i> 옵션품목 적용</a>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>옵션품목명</th>
            <th>추가 금액</th>
            <th>현재 재고</th>
            <th>품절 알림 재고</th>
            <th>출력 상태</th>
            <th>판매 상태</th>
            <th>관리</th>
          </tr>
        </thead>
        <tbody>
          <row-component v-for="(optionItem, key) in optionItemList" :key="key" v-bind:optionItemData="optionItem"
                       @remove="removeOptionItem"></row-component>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  // TODO : 저장된 옵션품목들은 그대로 유지되도록 구현필요
  import RowComponent from './RowComponent'
  import VueTagsInput from '@johmun/vue-tags-input'
  // combos : 가능한 모든 경우의 수를 구하는 함수 (옵션 조합)
  import combos from 'combos'

  export default {
    name: "OptionTableComponent",
    components: {
      RowComponent,
      VueTagsInput
    },
    props: [
      'options', 'optionItems', 'productId', 'productOptionType'
    ],
    data() {
      return {
        tmpTags: [],
        tmpTexts: [],
        tmpEditStates: [],
        isOptionListChanged: false,
        optionList: this.options,
        optionItemList: this.optionItems,
        optionType: this.productOptionType
      }
    },
    watch: {
      // optionList가 변화했는지 확인
      optionList: {
        deep: true,
        handler(e) {
          this.isOptionListChanged = true;
        }
      }
    },
    created() {
      // 옵션의 values를 tags 플러그인에 맞게 가공
      this.optionList.map((option, i) => {
        this.tmpTags[i] = option.values ? option.values.map(value => ({text:value})) : [];
      });
    },
    methods: {
      // 옵션 관련 함수
      addOption() {
        if(this.optionList.length <= 10) {
          this.optionList.push({
            product_id: this.productId,
            name: '',
            values: [],
            sort_order: 0
          });
          // 편집모드 활성화
          this.$set(this.tmpEditStates, this.optionList.length - 1, true);
        } else {
          alert('조합형 옵션은 최대 10개까지 만들 수 있습니다.');
        }
      },
      editOption(key) {
        this.$set(this.tmpEditStates, key, true);
      },
      saveOption(key) {
        this.$set(this.tmpEditStates, key, false);
        // 옵션품목 재생성
        this.createItemsByOption();
      },
      popOption(key) {
        this.optionList.splice(key, 1);
      },
      removeOptionItem(index) {
        this.optionItemList.splice(index, 1);
      },
      flushOptionItems() {
        // Vue에서는 배열을 비울때 splice(0)을 사용해야 안전하다
        this.optionItems.splice(0);
      },
      // 옵션값을 기반으로 n*n 품목을 만들어내는 함수
      createItemsByOption() {
        // 옵션명이 다 있는지 확인
        if(this.optionList.filter((option) => !option.name).length > 0) {
          alert('옵션명은 필수 입력입니다.');
          return;
        }
        // 옵션값이 다 았는지 확인
        if(this.optionList.filter((option) => !option.values.length).length > 0) {
          alert('옵션값은 필수 입력입니다.');
          return;
        }
        // 조합 일체선택형 & 조합 분리선택형
        if(this.optionType == 0 || this.optionType == 1) {
          let optionValuesMap = {};
          this.optionList.map((option) => {
            optionValuesMap[option.name] = option.values;
          });
          let combinedValueList = combos(optionValuesMap);
          if(combinedValueList.length > 1000) {
            alert('조합형 옵션은 조합결과가 1000가지인 경우까지만 허용됩니다');
            return;
          }
          this.optionItems.splice(0);
          combinedValueList.map((values) => {
            this.optionItems.push({
              product_id: this.productId,
              name: Object.values(values).join(', '),
              value_combination: values,
              addition_price: 0,
              sell_price: 0,
              stock: 0,
              alert_stock: 0,
              state_display: 1,
              state_deal: 1,
            });
          });
        }
        // 단독형이면
        if(this.optionType == 2) {
          this.optionItems.splice(0);
          this.optionList.map((option) => {
            option.values.map((value) => {
              this.optionItems.push({
                product_id: this.productId,
                name: value,
                value_combination: { [option.name] : value },
                addition_price: 0,
                sell_price: 0,
                stock: 0,
                alert_stock: 0,
                state_display: 1,
                state_deal: 1,
              });
            });
          });
        }
      },
      confirmAddItemsByOption() {
        if(confirm('기존 옵션품목은 초기화됩니다. 계속 진행하시겠습니까?')) {
          this.createItemsByOption();
        }
      },
      // 옵션 타입이 변화할때 옵션품목 초기화되는것을 확인
      confirmOptionTypeChange: function(event) {
        if(window.confirm("옵션타입을 변경하면 옵션품목이 초기화후 다시생성 됩니다. 계속 진행하시겠습니까?")) {
          this.optionType = event.target.value;
          // 옵션품목 재생성
          this.createItemsByOption();
        }
      }
    }
  }
</script>

<style scoped>

</style>
