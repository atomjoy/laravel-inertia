<script setup lang="ts">
import { columns } from '@/components/payments/columns';
import DataTable from '@/components/payments/DataTable.vue';
import { payments, type Payment } from '@/components/payments/types';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { users } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { PlusCircle } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
	{
		title: 'Users',
		href: users().url,
	},
];

const data = ref<Payment[]>([]);

// Fetch data from your API here.
async function getData(): Promise<Payment[]> {
	return payments;
}

onMounted(async () => {
	data.value = await getData();
});
</script>

<template>
	<Head title="Users" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
			<div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
				<div class="h-full flex-1 flex-col gap-8 p-5 md:flex">
					<div class="flex items-center justify-between gap-2">
						<div class="flex flex-col gap-1">
							<h2 class="text-2xl font-semibold tracking-tight">Payments</h2>
							<p class="text-muted-foreground">Here's a list of your payments for this month.</p>
						</div>
						<div class="flex items-center gap-2">
							<Link href="/payments">
								<Button class="inline-flex cursor-pointer items-center justify-center gap-2" variant="outline"> <PlusCircle /> Create </Button>
							</Link>

							<span data-slot="avatar" class="relative flex size-8 shrink-0 overflow-hidden rounded-full h-9 w-9">
                              <img role="img" src="/default/avatar.webp" data-slot="avatar-image" class="aspect-square size-full" alt="Image">
                            </span>
						</div>
					</div>

					<DataTable :columns="columns" :data="data" />
				</div>
			</div>
		</div>
	</AppLayout>
</template>
