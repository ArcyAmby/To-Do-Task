<template>
    <footer class="bg-light text-center py-3">
      <div class="container">
        <!-- Add your footer content here -->
        <p>&copy; {{ currentYear }} Your App Name. All rights reserved.</p>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    data() {
      return {
        currentYear: new Date().getFullYear(),
      };
    },
    // You can add component-specific logic here if needed
  };
  </script>
  
  <style>
  /* Add any custom footer styles here */
  </style>