<script setup lang="ts" generic="TData, TValue">
import DataTablePagination from '@/components/payments/DataTableCustomPagination.vue';
import DataTablePaginationNumbers from '@/components/payments/DataTablePaginationNumbers.vue';
import Filter from '@/components/payments/Filter.vue';
import type { PaginationState } from '@tanstack/vue-table';
import { filter_status } from '@/components/payments/types';
import { columns } from '@/components/payments/columns';
import { valueUpdater } from '@/components/ui/table/utils';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { ChevronDown } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import type { DateValue } from '@internationalized/date'
import { DateFormatter, getLocalTimeZone, today } from '@internationalized/date'
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
import DatePicker from '@/components/payments/DatePicker.vue';

// Page url
const table_request_url = 'payments';

const props = defineProps({
	data: Object,
	amount_max: Number,
	filter: Array,
	filter_errors: Object,
});

const filter_toolbar = [filter_status];
const slider = ref([0,props.amount_max ?? 10000])
const expanded = ref<ExpandedState>({});
const rowSelection = ref<RowSelectionState>({});
const columnVisibility = ref<VisibilityState>({
	// hide column by default
	// avatar: false,
});
const columnFilters = ref<ColumnFiltersState>([]);
const sorting = ref<SortingState>([
	{
		id: 'id',
		desc: true, // sort by age in descending order by default
	},
]);
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
	pageCount: props.data?.last_page ?? 1,
	// enableMultiRowSelection: true,
	// enableRowSelection: row => row.original.age > 18, //only enable row selection for adults
	// enableMultiRowSelection: false, //only allow a single row to be selected at once
	initialState: {
		pagination: {
			pageIndex: props.data?.current_page - 1,
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

		// Update backend here (required)
		router.get(
			table_request_url,
			{
				page: pagination.value.pageIndex + 1,
				per_page: pagination.value.pageSize,
				sort_field: sorting.value[0]?.id,
				sort_direction: sorting.value.length == 0 ? undefined : sorting.value[0]?.desc ? 'desc' : 'asc',
				...filters,
			},
			{ preserveState: true, preserveScroll: true },
		);
	}, 600),
});

// Date picker
const datePlaceholder = today(getLocalTimeZone())
const fdate = ref<DateValue>()
const tdate = ref<DateValue>()
const df = new DateFormatter('en-US', {
	dateStyle: 'short'
})

watch(props, (n) => {
	console.log("Props", n);
})
</script>

<template>
	<div class="full p-10">
		<div class="rounded-md border mb-4 p-4" v-if="sorting">{{ sorting }} {{ rowSelection }} {{ columnFilters }} {{ props.filter_errors }}</div>

		<div class="flex flex-col gap-1">
			<h2 class="text-2xl font-semibold tracking-tight">Payments</h2>
			<p class="text-muted-foreground">Here's a list of your payments for this year.</p>
		</div>

		<div class="flex flex-col my-2 lg:flex-row gap-2">
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
					:model-value="table.getColumn('email')?.getFilterValue() as string"
					@update:model-value="table.getColumn('email')?.setFilterValue($event)"
				/>

				<div v-for="filter in filter_toolbar" :key="filter.title" class="md:w-full mr-2 max-w-50">
					<Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
				</div>
			</div>

			<div class="w-full">
				<div class="text-xs">Price range ($<span class="font-medium tabular-nums">{{ slider[0] }}</span> - <span class="font-medium tabular-nums">{{ slider[1] }}</span>).</div>
				<Slider
					v-model="slider"
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
</template>