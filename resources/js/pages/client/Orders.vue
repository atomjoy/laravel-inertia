<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { orders } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { PlusCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: orders().url,
    },
];

let email = ref('')

function updateEmail(event: any) {
	console.log(event)
	// table.getColumn('email')?.setFilterValue($event)
}

</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-5">
			<!-- <div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"> -->
				<div class="flex h-full flex-col gap-5 p-0 md:flex">
					<div class="flex items-center justify-between gap-2">
						<div class="flex flex-col gap-1">
							<h2 class="text-2xl font-semibold tracking-tight">Orders</h2>
							<p class="text-muted-foreground">Here's a list of your orders for this month.</p>
						</div>

						<div class="flex items-center gap-1">
							<Link href="/orders/create">
								<Button class="inline-flex cursor-pointer items-center justify-center gap-2" variant="outline"> <PlusCircle /> Create </Button>
							</Link>
						</div>
					</div>

					<div class="w-full flex flex-row gap-2 flex-wrap">
						<Input
							:model-value="email"
							class="w-full h-9 min-w-50 max-w-50 mr-2" placeholder="Filter emails"
							@update:model-value="updateEmail($event)"
						/>
						<Button class="inline-flex cursor-pointer items-center justify-center gap-2" variant="outline"> <PlusCircle /> Filter </Button>
						<Button class="inline-flex cursor-pointer items-center justify-center gap-2" variant="outline"> <PlusCircle /> Filter </Button>
						<Button class="inline-flex cursor-pointer items-center justify-center gap-2" variant="outline"> <PlusCircle /> Filter </Button>
					</div>

					<div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
						<PlaceholderPattern />
						<!-- <pre class="p-4">{{ $page.props.auth }}</pre> -->
					</div>
				</div>
			<!-- </div> -->
        </div>
    </AppLayout>
</template>
