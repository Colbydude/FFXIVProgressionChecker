import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        achievements: null,
        achievementsLoading: false,
        character: null,
        characterLoading: false
    },

    mutations: {
        setAchievements (state, achievements) {
            state.achievements = achievements;
        },

        setAchievementsLoading (state, is_loading) {
            state.achievementsLoading = is_loading;
        },

        setCharacter (state, character) {
            state.character = character;
        },

        setCharacterLoading (state, is_loading) {
            state.characterLoading = is_loading;
        }
    },

    actions: {
        fetchAchievementsFromXIVDB (context, id) {
            $.ajax({
                url: 'https://api.xivdb.com/character/' + id + '?data=achievements_obtained',
                dataType: 'json',
            })
            .done(data =>  {
                context.commit('setAchievements', data);
                context.commit('setAchievementsLoading', false);
            })
            .fail(error => {
                console.log(error);
            });
        },

        fetchCharacterFromXIVDB (context, id) {
            $.ajax({
                url: 'https://api.xivdb.com/character/' + id,
                dataType: 'json',
            })
            .done(data =>  {
                context.commit('setCharacter', data);
                context.commit('setCharacterLoading', false);
            })
            .fail(error => {
                console.log(error);
            });
        }
    }
});
