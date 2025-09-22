<template>
    <div class="shift-summary">
        <div class="average">
            <div class="average-title">{{ title }}</div>
            <div class="average-value">{{ this.average.toFixed(1) ?? 0 }} {{ unit }}</div>
        </div>
        <div class="shifts">
            <div 
                v-for="(shift, index) in shifts" 
                :key="index" 
                :class="['shift', `shift-${index + 1}`]"
            >
                <span class="shift-name">{{ shift.name }}</span>
                <span class="shift-value">{{ shift.average.toFixed(1) }} {{ unit }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Array,
            required: true,
        },

        title: {
            type: String,
            required: true,
        },

        unit: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            shifts: [],
            average: null,
        };
    },

    watch: {
        data: {
            immediate: true,
            handler() {
                if (this.unit == 'шт') {
                    this.updateData();
                } else {
                    this.updateDataTime();
                }
            }
        }
    },

    methods: {
        updateData() {
            const shiftData = this.data.reduce((acc, operation) => {
                const letter = operation.letter;
                if (!acc[letter]) {
                    acc[letter] = {
                        name: `Смена ${letter}`,
                        operations: [],
                    };
                }
                if (operation?.operation?.type === 'main') {
                    acc[letter].operations.push(operation);
                }
                return acc;
            }, {});

            // Вычисляем количество и среднее для каждой смены
            this.shifts = Object.values(shiftData).map(shift => {
                const uniqueShifts = _.unionBy(shift.operations, 'work_shift_code').length;
                const totalUniqueOrders = shift.operations.length;
                const averagePerShift = uniqueShifts > 0 ? totalUniqueOrders / uniqueShifts : 0;
                return {
                    name: shift.name,
                    count: shift.operations.length,
                    average: averagePerShift,
                };
            }).sort((a, b) => 
                a.name.localeCompare(b.name, 'ru', { sensitivity: 'base' })
            );

            if (this.shifts.length > 0) {
                this.average = this.shifts.reduce((acc, shift) => acc + (shift.average || 0), 0) / 4;
            } else {
                this.average = null;
            }
        },

        updateDataTime() {
            const calculateWorkTime = (startDate, startTime, endDate, endTime) => {
                const startDateTime = new Date(`${startDate}T${startTime}`);
                let endDateTime = new Date(`${endDate}T${endTime}`);

                if (endDateTime < startDateTime) {
                    endDateTime.setDate(endDateTime.getDate() + 1);
                }

                const diffInMinutes = Math.round((endDateTime - startDateTime) / (1000 * 60));
                
                return diffInMinutes;
            };

            const shiftData = this.data.reduce((acc, operation) => {
                const letter = operation.letter;
                if (!acc[letter]) {
                    acc[letter] = {
                        name: `Смена ${letter}`,
                        operations: [],
                        fulltime: 0,
                    };
                }
                if (operation?.operation?.type === 'main') {
                    acc[letter].operations.push(operation);
                    acc[letter].fulltime += calculateWorkTime(operation.posting_date, operation.start_time, operation.posting_date, operation.end_time);
                }
                return acc;
            }, {});

            this.shifts = Object.values(shiftData).map(shift => {
                const totalUniqueOrders = shift.operations.length;
                const averagePerShift = shift.fulltime > 0 ? Math.round(shift.fulltime / totalUniqueOrders) : 0;
                return {
                    name: shift.name,
                    count: shift.operations.length,
                    average: averagePerShift,
                };
            }).sort((a, b) => 
                a.name.localeCompare(b.name, 'ru', { sensitivity: 'base' })
            );

            if (this.shifts.length > 0) {
                this.average = Math.round(  this.shifts.reduce((acc, shift) => acc + (shift.average || 0), 0) / 4);
            } else {
                this.average = null;
            }
        },
    },

    computed: {

    }
};
</script>

<style scoped>
.shift-summary {
    display: flex;
    flex-direction: column;
    padding: 16px;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.average {
    text-align: left;
    margin-bottom: 16px;
}

.average-title {
    font-family: Open Sans;
    font-size: 18px;
    font-weight: 400;
    color: #363F51;
}

.average-value {
    font-size: 28px;
    font-weight: 400;
    color: #363F51;
}

.shifts {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    width: 100%;
    justify-content: space-between;
}

.shift {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    min-width: 49%;
}

.shift-name {
    font-size: 18px;
    font-weight: 400;
    color: #363F51;
}

.shift-value {
    font-size: 18px;
    font-weight: 400;
    color: #363F51;  
}

.shift-1 {
    background-color: #e1f7e7;
    border: 1px solid #b2e2c1;
    color: #2e7d32;
}

.shift-2 {
    background-color: #ffedcc;
    border: 1px solid #ffcc80;
    color: #ef6c00;
}

.shift-3 {
    background-color: #e3f2fd;
    border: 1px solid #90caf9;
    color: #1e88e5;
}

.shift-4 {
    background-color: #fce4ec;
    border: 1px solid #f48fb1;
    color: #d81b60;
}
</style>
