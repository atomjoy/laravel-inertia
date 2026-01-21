<script setup lang="ts" generic="TData, TValue">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTablePagination from '@/components/payments/DataTableCustomPagination.vue';
import DataTablePaginationNumbers from '@/components/payments/DataTablePaginationNumbers.vue';
import Filter from '@/components/payments/Filter.vue';
import { filter_status } from '@/components/payments/types';
import { columns } from '@/components/payments/columns';
import { valueUpdater } from '@/components/ui/table/utils';
import { router } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import { ChevronDown } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { type PaginationState } from '@tanstack/vue-table';
import { type DateValue } from '@internationalized/date'
import { type BreadcrumbItem } from '@/types';
import { DateFormatter, getLocalTimeZone, today, CalendarDate, fromDate, } from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ColumnFiltersState, ExpandedState, FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, RowSelectionState, SortingState, useVueTable, VisibilityState, getFacetedMinMaxValues, getFacetedRowModel, getFacetedUniqueValues} from '@tanstack/vue-table';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Slider } from '@/components/ui/slider'
import throttle from 'lodash/throttle'
import debounce from 'lodash/debounce'
import { PlusCircle } from 'lucide-vue-next';
import { Head, Link } from '@inertiajs/vue3';
import payments from '@/routes/payments';

// Page url
const table_request_url = 'payments';

const props = defineProps({
	data: Object,
	amount_max: Number,
	filter: Object,
	filter_errors: Object,
	sort: Object,
});

const filter_toolbar = [filter_status];
const email = ref(props.filter?.email ?? '')
const amount = ref(props.filter?.amount)
const expanded = ref<ExpandedState>({});
const rowSelection = ref<RowSelectionState>({});
const columnFilters = ref<ColumnFiltersState>([]);
const sorting = ref<SortingState>([
	{
		id: 'id',
		desc: true, // sort by id in descending order by default
	}
]);
const columnVisibility = ref<VisibilityState>({
	// hide column by default
	// avatar: false,
});
const pagination = ref<PaginationState>({
	pageIndex: props.data?.current_page - 1,
	pageSize: props.data?.per_page,
});

// Update table state after on change events
// https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
// https://tanstack.com/table/latest/docs/guide/pagination#should-you-use-client-side-pagination
const table = useVueTable({
	manualPagination: true,
	manualSorting: true,
	manualFiltering: true,
	enableRowSelection: true,
	rowCount: props.data?.total ?? 0,
	// pageCount: props.data?.last_page ?? 1, // Error when paginate from page url query param !!!
	// enableMultiRowSelection: true,
	// enableRowSelection: row => row.original.age > 18, //only enable row selection for adults
	// enableMultiRowSelection: false, //only allow a single row to be selected at once
	initialState: {
		pagination: {
			pageIndex: 0,
			pageSize: props.data?.per_page,
		}
	},
	get data() {
		return props?.data?.data;
	},
	get columns() {
		return columns;
	},
	state: {
		get pagination() {
			return pagination.value;
		},
		get sorting() {
			return sorting.value;
		},
		get rowSelection() {
			return rowSelection.value;
		},
		get columnFilters() {
			return columnFilters.value;
		},
		get columnVisibility() {
			return columnVisibility.value;
		},
		get expanded() {
			return expanded.value;
		}
	},
	getRowId: (row) => row.id,
	getCoreRowModel: getCoreRowModel(),
	getSortedRowModel: getSortedRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	getFacetedRowModel: getFacetedRowModel(),
  	getFacetedUniqueValues: getFacetedUniqueValues(),
  	getFacetedMinMaxValues: getFacetedMinMaxValues(),
	getPaginationRowModel: getPaginationRowModel(), // Not required here
	onExpandedChange: (updaterOrValue) => valueUpdater(updaterOrValue, expanded),
	onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
	onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
	// Throttle or debounce requests
	onColumnFiltersChange: throttle((updaterOrValue) => {
		columnFilters.value = typeof updaterOrValue === 'function' ? updaterOrValue(columnFilters.value) : updaterOrValue;

		// Refresh data amd move to first page
		table.resetPageIndex()

		// Reset selection after filtering
		// rowSelection.value = {}

		// console.log(filters, table.getRowCount(), table.getPageCount(), props.data?.last_page, table.getState().pagination.pageIndex);
	}, 600),
	onSortingChange: throttle((updaterOrValue) => {
		sorting.value = typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue;

		// Refresh data amd move to first page
		table.resetPageIndex()
	}, 600),
	onPaginationChange: throttle((updaterOrValue) => {
		pagination.value = typeof updaterOrValue === 'function' ? updaterOrValue(pagination.value) : updaterOrValue;

		// Filters
		let filters = {};
		if (columnFilters.value) {
			filters = columnFilters.value.reduce((acc, filter) => {
				// @ts-ignore
        		acc[filter.id] = filter.value
        		return acc
      		}, {})
		}

		let sort = {};
		if (sorting.value) {
			sort = sorting.value.reduce((acc, obj) => {
				// @ts-ignore
        		acc[obj.id + '_desc'] = obj.desc
        		return acc
      		}, {})
		}

		// Update url query params here (required for server-side)
		router.get(
			table_request_url,
			{
				page: pagination.value.pageIndex + 1,
				per_page: pagination.value.pageSize,
				sort_field: sorting.value[0]?.id,
				sort_direction: sorting.value.length == 0 ? undefined : sorting.value[0]?.desc ? 'desc' : 'asc',
				sort: sort,
				...filters,
			},
			{ preserveState: true, preserveScroll: true },
		);
	}, 600),
});

// Date picker
const fdate = ref<DateValue>()
const tdate = ref<DateValue>()
const datePlaceholder = today(getLocalTimeZone())
const df = new DateFormatter('en-US', { dateStyle: 'short' })
let created_at = props.filter?.created_at ?? [datePlaceholder.subtract({days: 7}), datePlaceholder.add({ days: 1 })]
let d0 = new Date(created_at[0] as string)
let d1 = new Date(created_at[1] as string)
fdate.value = new CalendarDate(d0.getFullYear(), d0.getMonth() + 1, d0.getDate())
tdate.value = new CalendarDate(d1.getFullYear(), d1.getMonth() + 1, d1.getDate())
columnFilters.value.push({
	id: "created_at",
	value: [fdate.value.toString(), tdate.value.toString()]
})

watch(props, (n) => {
	console.log("Props", n);
})

// onMounted(() => {
// 	// Update filters from url query params (only sample)
// 	let params = new URLSearchParams(location.search)
// 	// console.log(Array.from(params.entries()), params.get('amount[1]'));

// 	// Date range filter
// 	let d0 = new Date(params.get('created_at[0]') as string)
// 	let d1 = new Date(params.get('created_at[1]') as string)
// 	fdate.value = new CalendarDate(d0.getFullYear(), d0.getMonth() + 1, d0.getDate())
// 	tdate.value = new CalendarDate(d1.getFullYear(), d1.getMonth() + 1, d1.getDate())
// 	if (params.get('created_at[0]') && params.get('created_at[1]')) {
// 		columnFilters.value.push({
// 			id: "created_at",
// 			value: [fdate.value.toString(), tdate.value.toString()]
// 		})
// 	}
// 	table.getColumn('created_at')?.setFilterValue(created_at);
// })

const breadcrumbs: BreadcrumbItem[] = [
	{
		title: 'Payments',
		href: payments.index().url,
	},
];
</script>

<template>
	<Head title="Payments" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
			<div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
				<div class="h-full flex-1 flex-col gap-6 p-5 md:flex">
					<div class="rounded-md border mb-4 p-4" v-if="sorting">{{ sorting }} {{ rowSelection }} {{ columnFilters }} {{ props.filter_errors }}</div>
					<div>
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

						<div class="filter-errors">
							<div class="filter-error py-1 text-sm text-red-400" v-for="err in props.filter_errors">{{ err[0] }}</div>
						</div>
					</div>

					<div class="w-full flex flex-col my-2 lg:flex-row gap-2">
						<div class="date-box flex">
							<Popover v-slot="{ close }">
								<PopoverTrigger as-child>
									<Button
										variant="outline"
										:class="cn('w-50 justify-start text-left font-normal mr-2', !fdate && 'text-muted-foreground')"
									>
										<CalendarIcon />
										{{ fdate ? df.format(fdate.toDate(getLocalTimeZone())) : "Start date" }}
									</Button>
								</PopoverTrigger>
								<PopoverContent class="w-auto p-0" align="start">
									<Calendar
										v-model="fdate"
										:default-placeholder="datePlaceholder"
										layout="month-and-year"
										initial-focus
										@update:model-value="() => {
											table.getColumn('created_at')?.setFilterValue([fdate?.toString(), tdate?.toString()]);
											close();
										}
										"
									/>
								</PopoverContent>
							</Popover>
							<Popover v-slot="{ close }">
								<PopoverTrigger as-child>
									<Button
										variant="outline"
										:class="cn('w-50 justify-start text-left font-normal', !tdate && 'text-muted-foreground')"
									>
										<CalendarIcon />
										{{ tdate ? df.format(tdate.toDate(getLocalTimeZone())) : "End date" }}
									</Button>
								</PopoverTrigger>
								<PopoverContent class="w-auto p-0" align="start">
									<Calendar
										v-model="tdate"
										:default-placeholder="datePlaceholder"
										layout="month-and-year"
										initial-focus
										@update:model-value="() => {
											table.getColumn('created_at')?.setFilterValue([fdate?.toString(), tdate?.toString()]);
											close();
										}
										"
									/>
								</PopoverContent>
							</Popover>
						</div>
						<div class="filter-box flex">
							<Input
								class="w-full h-9 min-w-50 max-w-50 mr-2" placeholder="Filter emails"
								:model-value="email"
								@update:model-value="table.getColumn('email')?.setFilterValue($event)"
							/>

							<div v-for="filter in filter_toolbar" :key="filter.title" class="md:w-full mr-2 max-w-50">
								<Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
							</div>
						</div>

						<div class="w-full">
							<div class="text-xs">Price range ($<span class="font-medium tabular-nums">{{ amount[0] }}</span> - <span class="font-medium tabular-nums">{{ amount[1] }}</span>).</div>
							<Slider
								v-model="amount"
								@update:model-value="table.getColumn('amount')?.setFilterValue($event)"
								:min="0"
								:max="amount_max"
								:step="0.5"
								class="mt-2 w-full mb-4"
								aria-label="Price Range"
							/>
						</div>

						<DropdownMenu>
							<DropdownMenuTrigger as-child>
								<Button variant="outline" class="ml-auto"> Columns <ChevronDown class="ml-2 h-4 w-4" /> </Button>
							</DropdownMenuTrigger>
							<DropdownMenuContent align="end">
								<DropdownMenuCheckboxItem
									v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
									:key="column.id"
									class="capitalize"
									:modelValue="column.getIsVisible()"
									@update:modelValue="
										(value: any) => {
											column.toggleVisibility(!!value);
										}
									"
								>
									{{ column.id }}
								</DropdownMenuCheckboxItem>
							</DropdownMenuContent>
						</DropdownMenu>
					</div>

					<div class="rounded-md border">
						<Table>
							<TableHeader>
								<TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
									<TableHead v-for="header in headerGroup.headers" :key="header.id">
										<FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
									</TableHead>
								</TableRow>
							</TableHeader>

							<TableBody>
								<template v-if="table.getRowModel().rows?.length">
									<TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
										<TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
											<FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
										</TableCell>
									</TableRow>
								</template>
								<template v-else>
									<TableRow>
										<TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
									</TableRow>
								</template>
							</TableBody>
						</Table>
					</div>

					<!-- <DataTablePagination :table="table" :last_page="props.data?.last_page ?? 0" /> -->

					<DataTablePaginationNumbers
						:table="table"
						:total="props.data?.total"
						:last-page="props.data?.last_page"
						:selected-rows="Object.entries(rowSelection).length"
					/>

				</div>
			</div>
		</div>
	</AppLayout>
</template>