<script setup>
import AdminContentWrapper from "@/Components/Partials/AdminContentWrapper.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineOptions({ layout: AdminLayout });
</script>

<template>
  <AdminContentWrapper>
    <section>
      <div class="grid grid-cols-12">
        <aside class="col-span-3 bg-[#242E35] border-r border-white rounded-md sticky">
          <div class="p-4 text-white font-[system-ui]">
            <p class="text-[1rem] font-bold">
              <a class="text-blue-400" href="#intro">Introduction</a>
            </p>
            <ul class="pl-2 mt-2 flex flex-col gap-[.15rem]">
              <li class="text-[14px]">
                <a class="text-blue-400" href="#patients">Patients</a>
              </li>
              <li class="text-[14px]">
                <a class="text-blue-400" href="#inventory">Inventory</a>
              </li>
              <li class="text-[14px]">
                <a class="text-blue-400" href="#lab-tests">Lab Tests</a>
              </li>
              <li class="text-[14px]">
                <a class="text-blue-400" href="#reports">Reports</a>
              </li>
              <li class="text-[14px]">
                <a class="text-blue-400" href="#notifications">Notifications</a>
              </li>
              <li class="text-[14px]">
                <a class="text-blue-400" href="#clarifications">Process Clarifications</a>
              </li>
            </ul>
          </div>
        </aside>
        <div
          class="col-span-9 bg-[#242E35] border-l border-white rounded-md min-h-[85vh]"
        >
          <div class="text-white font-[system-ui] px-8 py-6">
            <div class="block">
              <h2 id="intro" class="text-[1.65rem]">Introduction</h2>
              <p class="leading-6 text-[14px]">
                <b>PMS</b> is a web-based platform or system that handles daily
                transactions at the Island Medical Diagnostic Center Service Cooperative.
                This user guide can be used as a starting point to familiarize staff
                members regarding the features and functionalities of this platform.
              </p>
              <p class="leading-6 text-[14px]">
                There are 4 primary modules which are
                <a class="text-blue-400" :href="route('patients.index')">Patients</a>,
                <a class="text-blue-400" :href="route('inventory.index')">Inventory</a>,
                <a class="text-blue-400" :href="route('lab-tests.index')">Lab Tests</a>
                and
                <a class="text-blue-400" :href="route('reports.index')">Reports</a> that
                we will focus on.
              </p>
            </div>
            <div class="block mt-4">
              <h2 class="text-[1.65rem]" id="patients">Patients</h2>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Patients list</h3>
                <p class="leading-6 text-[14px]">
                  To view the primary list of patients on record, simply navigate to
                  Patients >
                  <a class="text-blue-400" :href="route('patients.index')"
                    >View Patients</a
                  >. You will be presented with a table of patients which you may search
                  using the search bar or filter using the input fields below the column
                  headings.
                </p>
                <p class="leading-6 text-[14px]">
                  Click the Last Name to view the patient profile.
                </p>
              </div>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Create a new patient</h3>
                <p class="leading-6 text-[14px]">
                  To begin patient recording process, we must first verify that the
                  patient exists in our patient records. If not, simply create a new
                  patient record by navigating to Patients >
                  <a class="text-blue-400" :href="route('patients.create')"
                    >Add New Patient</a
                  >
                  and fill out the form. After this, you should be able to create a new
                  patient transaction.
                </p>
              </div>

              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Create a patient transaction</h3>
                <p class="leading-6 text-[14px]">
                  After verifying that the patient exists in our records, navigate to a
                  patient profile and click the "New Transaction" button within the
                  Patient Transactions section which will then redirect you to the patient
                  transaction form. Refer to <i>Patients list</i> above on how to navigate
                  to a patient profile.
                </p>
                <p class="leading-6 text-[14px]">
                  Simply fill out the patient transaction form with the patient details.
                  Under the consumable sections, this is where you will define the lab
                  tests that the patient consumed during their visit:
                </p>
                <ol class="mt-4 ml-4 flex flex-col gap-[.25rem]">
                  <li class="text-[14px]">1. Select lab test and click add.</li>
                  <li class="text-[14px]">
                    2. Select inventory items and their quantities that are consumed for
                    the lab test.
                  </li>
                  <li class="text-[14px]">
                    3. Apply any discount or credits within the transaction summary.
                  </li>
                  <li class="text-[14px]">4. Click Save.</li>
                </ol>
              </div>

              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">
                  Managing a patient transaction
                </h3>
                <p class="leading-6 text-[14px]">
                  After creating a patient transaction, you will have the following
                  available capabilities:
                </p>

                <ol class="mt-4 ml-4 flex flex-col gap-[.25rem]">
                  <li class="text-[14px]">
                    1. Edit patient transaction personal information.
                  </li>
                  <li class="text-[14px]">
                    2. Upload lab results in the form of pdf or jpg/png.
                  </li>
                  <li class="text-[14px]">3. Mark a lab test as consumed.</li>
                  <li class="text-[14px]">4. Delete a lab test.</li>
                  <li class="text-[14px]">5. Mark a transaction as 100% paid for.</li>
                  <li class="text-[14px]">6. Attach an OR number to the transaction.</li>
                </ol>
              </div>
            </div>

            <div class="block mt-4">
              <h2 class="text-[1.65rem]" id="inventory">Inventory</h2>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Inventory list</h3>
                <p class="leading-6 text-[14px]">
                  To view the primary list of inventory items on record, simply navigate
                  to Inventory >
                  <a class="text-blue-400" :href="route('inventory.index')"
                    >View Inventory</a
                  >. You will be presented with a table of inventory items which you may
                  search using the search bar or filter using the input fields below the
                  column headings.
                </p>
                <p class="leading-6 text-[14px]">
                  Click the item name to view the inventory item details.
                </p>
              </div>

              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Create new inventory</h3>
                <p class="leading-6 text-[14px]">
                  To create new inventory, simply navigate to Inventory >
                  <a class="text-blue-400" :href="route('inventory.create')"
                    >Add New Inventory</a
                  >
                  and fill out the form.
                </p>
                <p class="leading-6 text-[14px]">
                  If you need to create a new inventory item category, simply navigate to
                  Inventory >
                  <a class="text-blue-400" :href="route('inventory-categories.index')"
                    >Categories</a
                  >
                  to create a new category which will be available for use immediately
                  after creation.
                </p>
              </div>

              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">
                  Create a new inventory transaction
                </h3>
                <p class="leading-6 text-[14px]">
                  To create new inventory transaction, simply navigate to an inventory
                  item detail page and select between the Increase or Decrease button to
                  make manual movements for things like new stock arrivals or lost/broken
                  items.
                </p>
              </div>
            </div>

            <div class="block mt-4">
              <h2 class="text-[1.65rem]" id="lab-tests">Lab Tests</h2>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Lab Tests list</h3>
                <p class="leading-6 text-[14px]">
                  To view the primary list of lab test on record, simply navigate to Lab
                  Tests >
                  <a class="text-blue-400" :href="route('lab-tests.index')"
                    >View Lab Tests</a
                  >. You will be presented with a table of lab tests which you may search
                  using the search bar or filter using the input fields below the column
                  headings.
                </p>
                <p class="leading-6 text-[14px]">
                  Click the test name to view the lab test details.
                </p>
              </div>

              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Create a lab test</h3>
                <p class="leading-6 text-[14px]">
                  To create new lab test, simply navigate to Lab Tests >
                  <a class="text-blue-400" :href="route('inventory.create')"
                    >Add New Lab Test</a
                  >
                  and fill out the form.
                </p>
                <p class="leading-6 text-[14px]">
                  If you need to create a new lab test category, simply navigate to
                  Inventory >
                  <a class="text-blue-400" :href="route('inventory-categories.index')"
                    >Categories</a
                  >
                  to create a new category which will be available for use immediately
                  after creation.
                </p>
              </div>
            </div>

            <div class="block mt-4" id="reports">
              <h2 class="text-[1.65rem]">Reports</h2>
              <p class="leading-6 text-[14px]">
                Report generation can be found at
                <a class="text-blue-400" :href="route('reports.index')">Reports</a> where
                you can generate a monthly cash flow report and a daily cash flow report.
              </p>
            </div>

            <div class="block mt-4" id="notifications">
              <h2 class="text-[1.65rem]">Notifications</h2>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Low Stock Inventory Notice</h3>
                <p class="leading-6 text-[14px]">
                  Users can set and view platform notifications for low stock on inventory
                  and expiration notices. Simply set the threshold notice value on the
                  inventory item either while creating or editing the item. When the item
                  meets this threshold, a notification will be sent to
                  <a class="text-blue-400" :href="route('notifications.index')"
                    >Notifications</a
                  >.
                </p>
                <p class="leading-6 text-[14px]">
                  Within the notifications list, you can mark a notification as read or
                  delete it.
                </p>
              </div>
            </div>

            <div class="block mt-4" id="clarifications">
              <h2 class="text-[1.65rem]">Process clarifications</h2>
              <div class="sub-block mt-2">
                <h3 class="text-[1.25rem] font-semibold">Clarification #1</h3>
                <p class="leading-6 text-[14px]">Add Content</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AdminContentWrapper>
</template>
<style scoped>
.sub-block {
  margin-inline-start: 0.5rem;
}
.block p {
  margin-bottom: 0.75rem;
}
</style>
