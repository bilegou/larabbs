<script src="{{ asset('js/vue.min.js') }}"></script>

<div v-bind:class="[activeClass, errorClass]"></div>


<script type="text/javascript">

var show = new Vue({

data: {
  activeClass: 'active',
  errorClass: 'text-danger'
}

})

</script>
