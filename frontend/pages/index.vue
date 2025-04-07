<template>
  <div class="text-center flex flex-col space-y-2">
    <h1 class="text-4xl text-slate">Recipe Search 3000</h1>
    <div class="text-slate-400 text-lg">The one recipe search to rule them all...</div>
  </div>
  <div class="flex flex-col space-y-2">
    <input type="text" v-model="keyword" placeholder="Search by keyword..." class="px-2 py-2 text-2xl w-full border border-solid border-slate-300 rounded-lg" />
    <div class="flex space-x-2">
      <div class="flex flex-col w-2/3">
        <input type="text" v-model="ingredient" placeholder="Ingredient" class=" h-[42px] px-2 py-2 border border-solid border-slate-300 rounded-lg" />
      </div>
      <div class="w-1/3">
        <input type="text" v-model="email" placeholder="Author Email" class="h-[42px] w-full px-2 py-2 border border-solid border-slate-300 rounded-lg" />
      </div>
    </div>
  </div>
  <div>
    <div class="flex flex-col divide-y divide-slate-300 px-2">
      <div v-if="pending">
        Loading...
      </div>
      <div v-else-if="data.data.length === 0">
        No Results Found
      </div>
      <div v-else class="py-4" v-for="recipe in data.data" :key="recipe.id">
        <h4 class="font-semibold text-xl"><NuxtLink :to="`/recipes/${recipe.slug}`" class="underline text-blue-500">{{ recipe.name }}</NuxtLink></h4>
        <h6 class="text-sm text-gray-500">{{ recipe.author }}</h6>
        <p v-if="recipe.description" class="mt-2">{{ recipe.description.slice(0, 85) }}...</p>
      </div>
    </div>
    <div class="mt-8">
      <ul class="list-none flex justify-between">
        <li><a @click.prevent="page--" href="#" class="px-4 py-2 text-slate-800 font-semibold border border-slate-300 hover:border-slate-800 rounded-lg">Previous</a></li>
        <li>Current Page: {{ page }}</li>
        <li><a @click.prevent="page++" href="#" class="px-4 py-2 text-slate-800 font-semibold border border-slate-300 hover:border-slate-800 rounded-lg">Next</a></li>
      </ul>
    </div>
  </div>
</template>
<script setup lang="js">
useHead({
  title: 'Recipe Search 3000'
});

const page = ref(1);
const email = ref();
const ingredient = ref();
const keyword = ref();

const filters = ref({
  email: '',
  ingredient: '',
  keyword: '',
  page: 1,
});

const timer = ref();

const { data, pending } = useFetch('http://127.0.0.1:8888/api/recipes', {
  server: false,
  query: filters
});

watch([email, ingredient, keyword, page], () => {
  clearTimeout(timer.value);
  timer.value = setTimeout(() => {
    filters.value.email = email.value;
    filters.value.ingredient = ingredient.value
    filters.value.keyword = keyword.value;
    filters.value.page = page.value;
  }, 300);
});
</script>
