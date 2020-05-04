<template>
  <div>
    <b-table
      :items="items"
      :fields="fields"
      responsive
      ref="table"
      :sort-compare-options="{ numeric: true, sensitivity: 'base' }"
    ></b-table>
    <div class="text-muted">
      <p v-if="loading">Loading...</p>
      <p>{{alert}}</p>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        timer: '',
        fields: [
          {
            key: '1',
            label: 'Code',
            sortable: true,
          },
          {
            key: 'ssname',
            label: 'Name',
            sortable: true,
          },
          {
            key: 'news',
            label: 'News',
            sortable: true,
          },
          {
            key: 'sslast',
            label: 'Price',
            sortable: true,
          },
          {
            key: '0',
            label: 'Float',
            sortable: true,
          },
          {
            key: 'ssvol',
            label: 'Vol.',
            sortable: true,
          },
          {
            key: 'ssdlr',
            label: '$',
            sortable: true,
          },
          {
            key: 'sscap',
            label: 'Cap',
            sortable: true,
          },
          {
            key: 'ssvol',
            label: 'Volume',
            sortable: true,
          },
          {
            key: 'chgopp',
            label: '% Chg',
            sortable: true,
          },
          {
            key: 'gap',
            label: '% Gap',
            sortable: true,
          },
          {
            key: 'sscomment',
            label: 'Analysis',
            sortable: false,
          },
          {
            key: 'sstime',
            label: 'Updated',
            sortable: true,
          },
        ],
        items: [],
        loading: true,
        alert: ''
      }
    },
    async created () {
        await axios.get('gappers')
            .then(response => {
                this.items = response.data
                this.loading = false;
                if (this.items.length == 0) {
                    this.alert = 'Nothing found.'
                } else {
                    this.alert = '';
                }
            })
            .catch(e => {
                console.log(e)
            })

        this.timer = setInterval(function () {
          axios.get('gappers')
            .then(response => {
                this.items = response.data
                if (this.items.length == 0) {
                    this.alert = 'Nothing found.'
                } else {
                    this.alert = '';
                }
            })
            .catch(e => {
                console.log(e)
            })
          this.$refs.table.refresh()
        }.bind(this), 10000); 
    },
  }
</script>