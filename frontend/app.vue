<template>
  <div class="max-w-2xl py-24 px-6 md:px-0 mx-auto">
    <div class="flex flex-col space-y-8">
      <div class="text-center flex flex-col space-y-2">
        <h1 class="text-4xl text-slate">Recipe Search 3000</h1>
        <div class="text-slate-400 text-lg">The one recipe search to rule them all...</div>
      </div>
      <div class="flex flex-col space-y-2">
        <input type="text" placeholder="Search by keyword..." class="px-2 py-2 text-2xl w-full border border-solid border-slate-300 rounded-lg">
        <div class="flex space-x-2">
          <div class="flex flex-col w-2/3">
            <input type="text" placeholder="Ingredients" class=" h-[42px] px-2 py-2 border border-solid border-slate-300 rounded-lg">
            <div class="text-xs text-slate-500 p-2">Separate multiple ingredients using commas. Ex: cranberry, turkey, potato</div>
          </div>
          <div class="w-1/3">
            <select class="h-[42px] w-full px-2 py-2 border border-solid border-slate-300 rounded-lg">
              <option>Author</option>
              <option>Test</option>
              <option>Test2</option>
            </select>
          </div>
        </div>
      </div>
      <div>
        <div class="flex flex-col divide-y divide-slate-300 px-2">
          <div class="py-4">
            <h4 class="font-semibold text-xl">The Best Chicken Biryani You'll Ever Eat</h4>
            <p>Quisque accumsan enim erat, ut tincidunt arcu commodo eu. Donec interdum ligula sed ligula rutrum, in posuere dui vulputate. Morbi hendrerit nibh at laoreet feugiat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec lobortis ante magna.</p>
          </div>
          <div class="py-4">Recipe 2</div>
          <div class="py-4">Recipe 3</div>
        </div>
        <div class="mt-8">
          <ul class="list-none flex justify-between">
            <li><a href="#" class="px-4 py-2 text-slate-800 font-semibold border border-slate-300 hover:border-slate-800 rounded-lg">Previous</a></li>
            <li><a href="#" class="px-4 py-2 text-slate-800 font-semibold border border-slate-300 hover:border-slate-800 rounded-lg">Next</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
useHead({
  title: 'Recipe Search 3000'
})
</script>
