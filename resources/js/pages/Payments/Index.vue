<script setup lang="ts" generic="TData, TValue">
import DataTablePagination from '@/components/payments/DataTableCustomPagination.vue';
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
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ColumnFiltersState, ExpandedState, FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, RowSelectionState, SortingState, useVueTable, VisibilityState, getFacetedMinMaxValues, getFacetedRowModel, getFacetedUniqueValues} from '@tanstack/vue-table';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Slider } from '@/components/ui/slider'

// Page url
const table_request_url = 'payments';

const props = defineProps({
	data: Object,
	slider_min: Number,
	slider_max: Number,
	filter: Array,
	filter_errors: Object,
});

const filter_toolbar = [filter_status];
const slider = ref([0,props.slider_max ?? 1000])
const expanded = ref<ExpandedState>({});
const rowSelection = ref<RowSelectionState>({});
const columnVisibility = ref<VisibilityState>({});
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
	// getPaginationRowModel: getPaginationRowModel(),
	onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
	onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
	onExpandedChange: (updaterOrValue) => valueUpdater(updaterOrValue, expanded),
	onColumnFiltersChange: (updaterOrValue) => {
		columnFilters.value = typeof updaterOrValue === 'function' ? updaterOrValue(columnFilters.value) : updaterOrValue;

		// Filters
		let filters = {};
		if (columnFilters.value) {
			filters = columnFilters.value.reduce((acc, filter) => {
				// @ts-ignore
        		acc[filter.id] = filter.value
        		return acc
      		}, {})
		}

		// Update backend here
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

		table.resetPageIndex()
		// table.resetPageSize()

		// console.log(filters, table.getRowCount(), table.getPageCount(), props.data?.last_page);
	},
	onSortingChange: (updaterOrValue) => {
		sorting.value = typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue;

		// Filters
		let filters = {};
		if (columnFilters.value) {
			filters = columnFilters.value.reduce((acc, filter) => {
				// @ts-ignore
        		acc[filter.id] = filter.value
        		return acc
      		}, {})
		}

		// Update backend here
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
	},
	onPaginationChange: (updaterOrValue) => {
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

		// Update backend here
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
	},
});

watch(props, (n) => {
	console.log("Props", n);
})
</script>

<template>
	<div class="full p-10">
		<div class="rounded-md border mb-4 p-4" v-if="sorting">{{ sorting }} {{ rowSelection }} {{ columnFilters }} {{ props.filter_errors }}</div>

		<div class="flex items-center my-4">
			<Input
				class="h-9 max-w-50 mr-2" placeholder="Filter emails"
				:model-value="table.getColumn('email')?.getFilterValue() as string"
				@update:model-value="table.getColumn('email')?.setFilterValue($event)"
			/>

			<div v-for="filter in filter_toolbar" :key="filter.title" class="mr-2">
				<Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
			</div>

			<div class="w-full max-w-60 mx-2">
				<div class="text-xs">Price range ($<span class="font-medium tabular-nums">{{ slider[0] }}</span> - <span class="font-medium tabular-nums">{{ slider[1] }}</span>).</div>
				<Slider
					v-model="slider"
					@update:model-value="table.getColumn('amount')?.setFilterValue($event)"
					:min="slider_min"
					:max="slider_max"
					:step="10"
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

		<DataTablePagination :table="table" :last_page="props.data?.last_page ?? 0" />
	</div>
</template>

<style>
.payment-status {
	float: left;
	padding: 5px 10px;
	border-radius: 50px;
	background: #f9f9f9;
	color: #222;
}
.status-success {
	color: #55cc55 !important;
	background: #55cc5522 !important;
}
.status-success svg {
	stroke: #55cc55 !important;
}
.status-canceled {
	color: #f1ce0d !important;
	background: #f1ce0d22 !important;
}
.status-canceled svg {
	stroke: #f1ce0d !important;
}
.status-failed {
	color: #ff2233 !important;
	background: #ff223322 !important;
}
.status-failed svg {
	stroke: #ff2233 !important;
}
.status-processing {
	color: #0077ff !important;
	background: #0077ff22 !important;
}
.status-processing svg {
	stroke: #0077ff !important;
}
</style>
