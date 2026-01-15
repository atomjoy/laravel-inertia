import { h } from 'vue'
import {
  CheckCircle,
  CircleOff,
  HelpCircle,
  Timer,
} from 'lucide-vue-next'

export interface Payment {
  id: string
  amount: number
  status: 'pending' | 'success' | 'failed' | 'canceled' | 'processing'
  email: string
}

// Statuses
export const statuses = [
  {
    value: 'failed',
    label: 'Failed',
    icon: h(CircleOff),
  },
  {
    value: 'pending',
    label: 'Pending',
    icon: h(HelpCircle),
  },
  {
    value: 'canceled',
    label: 'Canceled',
    icon: h(CircleOff),
  },
  {
    value: 'success',
    label: 'Success',
    icon: h(CheckCircle),
  },
  {
    value: 'processing',
    label: 'Processing',
    icon: h(Timer),
  },
]

export const filter_status = {
  title: 'Filter status',
  column: 'status',
  data: statuses
}

// Table data
export const payments: Payment[] = [
  {
    id: '1',
    amount: 10,
    status: 'success',
    email: 'm@example.com',
  },
  {
    id: '2',
    amount: 20,
    status: 'failed',
    email: 'example@gmail.com',
  },
  {
    id: '3',
    amount: 30,
    status: 'pending',
    email: 'm@example.com',
  },
  {
    id: '4',
    amount: 45,
    status: 'processing',
    email: 'example@gmail.com',
  },
  {
    id: '5',
    amount: 100,
    status: 'success',
    email: 'm@example.com',
  },
  {
    id: '6',
    amount: 125,
    status: 'processing',
    email: 'example@gmail.com',
  },
  {
    id: '7',
    amount: 100,
    status: 'pending',
    email: 'm@example.com',
  },
  {
    id: '8',
    amount: 125,
    status: 'success',
    email: 'example@gmail.com',
  },
  {
    id: '9',
    amount: 100,
    status: 'failed',
    email: 'm@example.com',
  },
  {
    id: '10',
    amount: 125,
    status: 'processing',
    email: 'example@gmail.com',
  },
  {
    id: '11',
    amount: 10,
    status: 'pending',
    email: 'm@example.com',
  },
  {
    id: '12',
    amount: 15,
    status: 'canceled',
    email: 'ele@gmail.com',
  },
]
