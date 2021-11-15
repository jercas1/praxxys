<template>
  <div>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Search product"
      />

      <select class="custom-select" id="inputGroupSelect01">
        <option selected>Product category</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>

      <div class="input-group-append">
        <button
          class="btn btn-outline-success"
          type="button"
        >
          Search
        </button>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Category</th>
          <th scope="col">Description</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>