<template>
    <div>
        <input type="hidden" name="newCategory" v-model="categoryString">

        <create-category-component v-for="(component, componentIndex) in this.createComponents"
                                   v-on:selectCategoryItem="selectCategoryItem"
                                   v-on:addCreateComponent="addCreateComponent"
                                   v-on:removeCreateComponent="removeCreateComponent"
                                   :category-items="categoryItems"
                                   :get-child-url="getChildUrl"
                                   :componentIndex="componentIndex"
                                   :key="componentIndex">
        </create-category-component>
    </div>
</template>

<script>
    export default {
        name: "CategoryComponent",
        props: ['categoryItems', 'mode', 'getChildUrl'],
        data() {
            return {
                createComponents: [],
                newCategoryItems : [],
                categoryString : '',
            }
        },
        mounted() {
            this.createComponents.push('');
        },
        methods: {
            selectCategoryItem: function (componentIndex, itemId) {
                this.createComponents[componentIndex] = itemId;

                if (this.newCategoryItems.length >= componentIndex+1) {
                    this.newCategoryItems[componentIndex] = itemId;
                } else {
                    this.newCategoryItems.push(itemId);
                }

                this.generateCategoryString();
            },
            generateCategoryString: function () {
                var string = '';

                for (var index in this.newCategoryItems) {
                    string += this.newCategoryItems[index] + ',';
                }

                this.categoryString = string;
            },
            addCreateComponent: function () {
                this.createComponents.push('');
            },
            removeCreateComponent: function (componentIndex) {
                this.createComponents.splice(componentIndex, 1);

                if (this.createComponents.length === 0) {
                    this.createComponents.push('');
                }
            }
        }
    }
</script>

<style scoped>

</style>
