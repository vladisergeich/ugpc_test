<template>
    <div class="fact-card">
        <div class="fact-title">
            <span class="title">Трата меди за период</span>
        </div>
        <div class="fact-details">
            <a-table 
                :columns="columns" 
                :data-source="tableData" 
                size="small"
                bordered
            >
            </a-table>
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
    },
    data() {
      return {
        tableData: [],
        columns: []
      };
    },

    watch: {
        data: {
            immediate: true,
            handler() {
                this.generateTableData();
            }
        }
    },

    computed: {

    },
    methods: {
        generateTableData() {
            const groupedData = {};
            const monthSet = new Set();

            // Шаг 1: Группировка данных по сменам и месяцам
            this.data.forEach((item) => {
                const date = new Date(item.posting_date);
                const month = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}`; // Формат "2024-01"
                
                // Добавляем месяц в Set, чтобы собрать уникальные месяцы
                monthSet.add(month);

                if (!groupedData['thickness']) {
                    groupedData['thickness'] = { category: 'Толщина', total: 0 };
                }

                if (!groupedData['anodes']) {
                    groupedData['anodes'] = { category: 'Расход анодов теорит-ая, кг', total: 0 };
                }

                // Инициализируем месяц для данной смены, если его еще нет
                if (!groupedData['thickness'][month]) {
                    groupedData['thickness'][month] = 0;
                }

                if (!groupedData['anodes'][month]) {
                    groupedData['anodes'][month] = 0;
                }

                console.log(item);
                // Добавляем количество операций для смены и месяца
                groupedData['thickness'][month] += item?.thickness?.float_value;
                groupedData['thickness'].total += item?.thickness?.float_value; // Добавляем в итог

                groupedData['anodes'][month] += (item?.thickness?.float_value*(( (1.57*(item?.engraving_order?.format/31.4)*(item?.engraving_order?.format/31.4)) + (item?.engraving_order?.format*13.8) - 266)/10000)*8.9*1.06)/1000;
                groupedData['anodes'].total += (item?.thickness?.float_value*(( (1.57*(item?.engraving_order?.format/31.4)*(item?.engraving_order?.format/31.4)) + (item?.engraving_order?.format*13.8) - 266)/10000)*8.9*1.06)/1000; // Добавляем в итог
            });

            this.columns = [
                { 
                    title: '', 
                    dataIndex: 'category', 
                    key: 'category',
                    customHeaderCell: (column) => ({
                        style: {
                            background: '#F7F7F8',
                        },
                    }), 
                },
                { 
                    title: 'Итого', 
                    dataIndex: 'total', 
                    key: 'total',
                    customHeaderCell: (column) => ({
                        style: {
                            background: '#F7F7F8',
                        },
                    }), 
                }
            ];

            // Добавляем динамически колонки для каждого уникального месяца
            Array.from(monthSet).sort().forEach((month) => {
                const [year, monthNumber] = month.split('-');
                const monthNames = ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"];
                const monthLabel = `${monthNames[parseInt(monthNumber, 10) - 1]} ${year}`; // Формат "янв 2024"
                this.columns.push(
                    { 
                        title: monthLabel, 
                        dataIndex: month, 
                        key: month,
                        customHeaderCell: (column) => ({
                            style: {
                                background: '#F7F7F8',
                            },
                        }), 
                    }
                );
            });

            // Шаг 3: Формируем данные для таблицы
            this.tableData = Object.values(groupedData);
        }
    },
  };
  </script>
  
  <style scoped>  
  .fact-card {
    background: #FFFFFF;
    box-shadow: 0px 4px 20px 0px #BDBDBD40;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
  }

  .fact-title {
    border-bottom: 1px solid #E8E8E8
  }
  
  .title {
    font-size: 18px;
    font-weight: 600;
    text-align: left;
    color: #363F51;
  }

  .fact-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
  }
  </style>
  