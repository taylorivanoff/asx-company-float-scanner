<template>
    <div class="row">
        <div class="col-sm-3 col-5">
            <el-select
                v-model="value"
                filterable
                remote
                clearable
                reserve-keyword
                autocomplete
                default-first-option
                placeholder="XYZ.AX"
                :remote-method="remoteMethod"
                change="selectionChanged"
                class="text-monospace mb-4"
                :loading="loading">
                    <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item"
                      class="text-monospace"
                    >
                    </el-option>
            </el-select>
        </div>
        <div class="col-sm-9 col-7">
            <p class="text-success text-monospace h5 pt-2" v-if="ticker">
                {{ ticker.float }}
            </p> 
        </div>
    </div>              
</template>

<script>
  export default {
    data() {
      return {
        options: [],
        value: [],
        list: [],
        loading: false,
        companies: []
      }
    },
    async created () {
        await axios.get('companies')
            .then(response => {
                this.companies = response.data
            })
            .catch(e => {
                console.log(e)
            })

        this.list = this.companies.map(item => {
            return {
                value: `${item.code}`,
                label: `${item.code}`,
                data: item
            };
        });
    },
    computed: {
        ticker: function () {
            return this.value.data
        }
    },
    methods: {
      remoteMethod(query) {
        if (query !== '') {
          this.loading = true;
          setTimeout(() => {
            this.loading = false;
            this.options = this.list.filter(item => {
                return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
            });
          }, 1);
        } else {
          this.options = [];
        }
      },
    }
  }
</script>