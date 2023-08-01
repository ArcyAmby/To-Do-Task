<template>
  <div>
    <h2 v-if="isNewTask"></h2>
    <h2 v-else></h2>
    <form @submit.prevent="submitForm" class="modal-form">
      <div class="container">
        <div class="row mb-3">
          <label for="title" class="col-sm-2 col-form-label">Title:</label>
          <div class="col-sm-12">
            <input class="form-control" type="text" id="title" v-model="task.title" required />
          </div>
        </div>
        <div class="row mb-3">
          <label for="description" class="col-sm-2 col-form-label">Description:</label>
          <div class="col-sm-12">
            <textarea class="form-control" id="description" v-model="task.description" required></textarea>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Status:</label>
          <div class="col-sm-10">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="pending" value="Pending" v-model="task.status" required>
              <label class="form-check-label" for="pending">Pending</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="ongoing" value="Ongoing" v-model="task.status" required>
              <label class="form-check-label" for="ongoing">Ongoing</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="completed" value="Completed" v-model="task.status" required>
              <label class="form-check-label" for="completed">Completed</label>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <label for="file" class="col-sm-2 col-form-label">File:</label>
          <div class="col-sm-12">
            <input type="file" id="file" @change="onFileChange" accept=".pdf" />
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary" v-if="isNewTask">Add Task</button>
            <!-- <button type="submit" class="btn btn-primary" v-else>Update Task</button> -->
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    task: Object,
    isNewTask: Boolean,
  },
  data() {
    return {
      buttonColor: '#F2AA4CFF',
      taskData: {}
    };
  },
  created() {
    // Add a console.log statement to check the value of this.task
    console.log(this.task);
    this.taskData = { ...this.task };
  },
  methods: {
    // Method to handle file selection
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        // Validate if the selected file is a PDF
        if (!file.type.includes('pdf')) {
          // Show a warning message if the file is not a PDF
          this.isPdfFile = false;
          this.errorMessage = 'Only PDF files are allowed.';
          return;
        }

        // Update the taskToEdit.file data to the new file
        this.taskToEdit.file = file;

        // Clear the error message if the selected file is a PDF
        this.isPdfFile = true;
        this.errorMessage = '';

        // Create a FileReader to read the file and display its name
        const reader = new FileReader();
        reader.onload = () => {
          this.filePreview = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },
    async submitForm() {
      try {
        if (this.isNewTask) {
          // Perform file upload if a file is selected
          if (this.task.file) {
            const formData = new FormData();
            formData.append('file', this.task.file);

            // Upload the file to the server (backend) using Laravel's storage functionality
            try {
              const response = await axios.post('/api/tasks/upload-file', formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });

              // Set the file name and path returned from the server to the task object
              this.task.file_name = response.data.file_name;
              this.task.file_path = response.data.file_path;
            } catch (error) {
              console.error('Error uploading file:', error);
            }
          }

          await axios.post('/api/tasks', this.task);

            // Emit an event to notify the parent component (App) about the new task creation
            this.$emit('new-task-created', this.taskData);
          // ...

          // Clear the form fields
          this.task.title = '';
          this.task.description = '';
          this.task.status = '';
          this.task.file = null;

        } else {
          await axios.put(`/api/tasks/${this.task.id}`, this.task);
          // // Fetch the updated task details from the server
          // const response = await axios.get(`/api/tasks/${this.task.id}`);
          // this.task = response.data;
        }

        this.$parent.fetchTasks(); // Add this line to reload the tasks data in TaskList

        // Emit an event to notify the parent component that a new task has been created or an existing task has been updated
        this.$emit('task-updated', this.task);

        console.log('Submitted Task Data:', this.task);

      } catch (error) {
        console.error(error.response.data);
      }
    }
  }
};
</script>

<style>

</style>
