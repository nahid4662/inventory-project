<script setup>

import { usePage } from "@inertiajs/vue3";
const page = usePage();
const customer = page.props.customer;
const invoice = page.props.invoice;
const product = page.props.product;

</script>

<template>

<template>
	</template>
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-md-10">
					<div class="card shadow-sm border-0">
						<div class="card-header bg-primary text-white">
							<h5 class="mb-0">Invoice Details</h5>
						</div>
						<div class="card-body">
							<div v-if="invoice">
								<h6>Customer Info</h6>
								<p><strong>Name:</strong> {{ customer?.name }}</p>
								<p><strong>Mobile:</strong> {{ customer?.mobile }}</p>
								<hr>
								<h6>Invoice Info</h6>
								<p><strong>Total:</strong> {{ invoice?.total }}</p>
								<p><strong>Discount:</strong> {{ invoice?.discount }}</p>
								<p><strong>VAT:</strong> {{ invoice?.vat }}</p>
								<p><strong>Payable:</strong> {{ invoice?.payable }}</p>
								<hr>
								<h6>Products</h6>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Product</th>
											<th>Qty</th>
											<th>Sale Price</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="prod in product" :key="prod.id">
											<td>{{ prod.product?.name }}</td>
											<td>{{ prod.qty }}</td>
											<td>{{ prod.sale_price }}</td>
											<td>{{ (prod.qty * prod.sale_price).toFixed(2) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div v-else>
								<p>No invoice details found.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</template>

<style scoped>

</style>
