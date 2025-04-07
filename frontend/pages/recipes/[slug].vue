<template>
  <div>
    <div v-if="pending">
      Loading...
    </div>
    <div v-else class="flex flex-col space-y-4">
      <div><NuxtLink to="/" class="underline text-blue-500">&lt; Back to Search</NuxtLink></div>
      <div>
        <h1 class="text-4xl text-slate">{{ recipe.name }}</h1>
        <div class="text-sm text-gray-500">{{ recipe.author }}</div>
      </div>

      <div class="mt-2">
        {{ recipe.description }}
      </div>
      <div>
        <h4 class="font-semibold text-2xl">Ingredients</h4>
        <ul class="mt-2 space-y-0.5">
          <li v-for="ingredient in recipe.ingredients" class="grid grid-cols-6">
            <div class="col-span-2">{{ ingredient.amount }} {{ ingredient.unit }}</div>
            <div class="col-span-4">{{ ingredient.name }}</div>
          </li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold text-2xl">Steps</h4>
        <ol class="mt-2 list-decimal pl-4 space-y-1">
          <li v-for="step in recipe.steps">
            {{ step.description }}
          </li>
        </ol>
      </div>
    </div>
  </div>
</template>
<script setup lang="js">
const route = useRoute();
const recipe = ref(null);

const { data, error, pending } = await useFetch(`http://localhost:8888/api/recipes/${route.params.slug}`, {
  server: false,
  onResponse({ response }) {
    recipe.value = response._data.data
  }
});
</script>
