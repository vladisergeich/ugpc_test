import axios from 'axios';

const state = {
    selectedMachine: null,
    machine: {},
    warehouseShafts: [],
    operations: [],
    secondOperations: [],
    currentOperation: null,
    consumpShaft: null,
    engravingOrder: null,
    preCopper: false,
    engravingHeads: [],
};
 
const getters = {
    getWarehouseShafts(state) {
        return state.warehouseShafts
    },

    getOperationsMachine(state) {
        return state.operations
    },

    getSecondOperationsMachine(state) {
        return state.secondOperations
    },

    getMachine(state) {
        return state.selectedMachine
    },

    getCurrentOperation(state) {
        return state.currentOperation
    },

    getConsumpShaft(state) {
        return state.consumpShaft
    },

    getEngravingOrder(state) {
        return state.engravingOrder
    },

    getPreCopper(state) {
        return state.preCopper
    },

    getEngravingHeads(state) {
        return state.engravingHeads
    },
};
 
const mutations = {
    SET_SELECTED_MACHINE(state, machine) {
        state.selectedMachine = machine;
    },

    SET_PRE_COPPER(state, preCopper) {
        state.preCopper = preCopper;
    },

    SET_WAREHOUSE_SHAFTS(state, shafts) {
        state.warehouseShafts = shafts;
    },

    SET_OPERATIONS_MACHINE(state, operations) {
        state.operations = operations;
    },

    SET_SECOND_OPERATIONS_MACHINE(state, operations) {
        state.secondOperations = operations;
    },

    SET_CURRENT_OPERATION(state, operation) {
        state.currentOperation = operation;
    },

    SET_CONSUMP_SHAFT(state, shaft) {
        state.consumpShaft = shaft;
    },

    SET_ENGRAVING_ORDER(state, order) {
        state.engravingOrder = order;
    },

    SET_ENGRAVING_HEADS(state, heads) {
        state.engravingHeads = heads;
    },

    ADD_SHAFT(state, shaft) {
        state.warehouseShafts.push(shaft);
    },
};
 
const actions = {
    getWarehouseShafts (context) {
        axios
            .post(route('ugpc.getwarehouseshafts'),{machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_WAREHOUSE_SHAFTS', response.data);
            });
    },

    getOperationsMachine (context) {
        axios
            .post(route('ugpc.getoperationsmachine'),{machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_OPERATIONS_MACHINE', response.data);
            });
    },

    getSecondOperationMachine (context) {
        axios
            .post(route('ugpc.getsecondoperationsmachine'),{machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_SECOND_OPERATIONS_MACHINE', response.data);
            });
    },

    getCurrentOperation (context) {
        axios
            .post(route('ugpc.getcurrentoperation'),{machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_CURRENT_OPERATION', response.data);
            });
    },

    startOperation (context,operationId) {
        axios
            .post(route('ugpc.startoperation'),{operationId: operationId, machineId: state.selectedMachine.id, shaft: state.consumpShaft})
            .then(response => {
                context.commit('SET_OPERATIONS_MACHINE', response.data.operations.filter((operation) => operation.machine_id == state.selectedMachine.id)); 
                context.commit('SET_CURRENT_OPERATION', response.data.currentOperation);    
            });
    },

    deleteOperation (context) {
        axios
            .post(route('ugpc.deleteoperation'),{currentOperation: state.currentOperation,machineId: state.selectedMachine.id,})
            .then(response => {
                context.commit('SET_OPERATIONS_MACHINE', response.data.operations); 
                context.commit('SET_CURRENT_OPERATION', response.data.currentOperation);    
            });
    },

    closeOperation (context) {
        axios
            .post(route('ugpc.closeoperation'),{shaft: state.consumpShaft, currentOperation: state.currentOperation,machineId: state.selectedMachine.id,})
            .then(response => {
                context.commit('SET_CONSUMP_SHAFT', null); 
                context.commit('SET_OPERATIONS_MACHINE', response.data.operations.filter((operation) => operation.machine_id == state.selectedMachine.id)); 
                context.commit('SET_CURRENT_OPERATION', response.data.currentOperation);    
            });
    },

    addPreCopperPlating (context) {
        axios
            .post(route('ugpc.addprecopperplating'),{shaft: state.consumpShaft, currentOperation: state.currentOperation,machineId: state.selectedMachine.id,})
            .then(response => {
                context.commit('SET_CONSUMP_SHAFT', null); 
                context.commit('SET_OPERATIONS_MACHINE', response.data.operations); 
                context.commit('SET_CURRENT_OPERATION', response.data.currentOperation);    
            });
    },

    consumpShaft (context,shaft) {
        axios
            .post(route('ugpc.consumpshaft'),{engravingOrder: shaft,machineId: state.selectedMachine.id})
            .then(response => {
                if (response.data) {
                    context.commit('SET_CONSUMP_SHAFT', shaft); 
                    context.commit('SET_WAREHOUSE_SHAFTS', response.data);   
                } else {
                    alert('Данная машина занята');
                }
            });
    },

    deleteConsumpShaft (context) {
        axios
            .post(route('ugpc.deleteconsumpshaft'),{engravingOrder: state.consumpShaft,machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_CONSUMP_SHAFT', null); 
                context.commit('SET_WAREHOUSE_SHAFTS', response.data);   
            });
    },

    getСonsumedShaft (context) {
        axios
            .post(route('ugpc.getconsumpshaft'),{machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_CONSUMP_SHAFT', response.data);    
            });
    },

    selectMachine({ commit }, machine) {
        commit('SET_SELECTED_MACHINE', machine);
    },

    setPreCopper({ commit }, preCopper) {
        commit('SET_PRE_COPPER', preCopper);
    },


    defectShaft (context) {
        axios
            .post(route('ugpc.defectshaft'),{engravingOrder: state.consumpShaft,currentOperation: state.currentOperation, machineId: state.selectedMachine.id})
            .then(response => {
                context.commit('SET_CONSUMP_SHAFT', null); 
                context.commit('SET_OPERATIONS_MACHINE', response.data); 
                context.commit('SET_CURRENT_OPERATION', null);    
            });
    },

    alterationStage (context,param) {
        axios
            .post(route('ugpc.alterationstage'),{orderStage: param.orderStage,alterationStages: param.alterationStages})
            .then(response => {
                context.commit('SET_ENGRAVING_ORDER', response.data); 
            });
    },

    skipDefect (context,param) {
        axios
            .post(route('ugpc.skipdefect'),{orderStage: param.orderStage})
            .then(response => {
                context.commit('SET_ENGRAVING_ORDER', response.data); 
            });
    },

    replaceShaft(context,param) {
        axios
            .post(route('ugpc.replaceshaft'),{orderStage: param.orderStage})
            .then(response => {
                context.commit('SET_ENGRAVING_ORDER', response.data); 
            });
    },

    setEngravingHeads(context,heads) {
        context.commit('SET_ENGRAVING_HEADS',heads)
    },

    setEngravingOrder(context,order) {
        context.commit('SET_ENGRAVING_ORDER',order)
    },

    addShaft({ commit }, shaft) {
        commit('ADD_SHAFT', shaft);
    },

};
 
export default {
    state,
    getters,
    mutations,
    actions
}