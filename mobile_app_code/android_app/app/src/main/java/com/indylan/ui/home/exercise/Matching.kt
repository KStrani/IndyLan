package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.view.doOnLayout
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.data.model.ExerciseMatchAnswer
import com.indylan.data.model.OptionMatch
import com.indylan.databinding.FragmentExerciseMatchingBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class MatchingExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseMatchingBinding
    lateinit var exerciseMatchAnswer: ExerciseMatchAnswer
    private var answeredWrong = false
    private val questionAdapter: OptionsMatchAdapter by lazy {
        OptionsMatchAdapter(binding.recyclerViewQuestions, true) { position, question ->
            selectedQuestion = question
            questionAdapter.setSelectedPosition(position)
            answerAdapter.correctAnswerPosition =
                answerAdapter.findCorrectAnswerPosition(question.wordId)
            answerAdapter.enableAllButtons()
        }
    }
    private val answerAdapter: OptionsMatchAdapter by lazy {
        OptionsMatchAdapter(binding.recyclerViewAnswers, false) { position, answer ->
            selectedQuestion?.let { que ->
                if (position == answerAdapter.correctAnswerPosition) {
                    if (filteredQuestions.size == 1) {
                        if (!answeredWrong) {
                            (parentFragment as? ExerciseFragment)?.increaseScore()
                        }
                        (parentFragment as? ExerciseFragment)?.nextQuestion()
                    } else {
                        removeQuestionAndAnswer(que, answer)
                    }
                } else {
                    answeredWrong = true
                    answerAdapter.showIncorrectAnswer(position)
                }
            }
        }
    }
    var selectedQuestion: OptionMatch? = null
    var selectedAnswer: OptionMatch? = null
    private val filteredQuestions: MutableList<OptionMatch> = mutableListOf()
    private val filteredAnswers: MutableList<OptionMatch> = mutableListOf()

    override fun onFocusGained() {
        view?.doOnLayout {
            (parentFragment as ExerciseFragment).showExerciseTypeName()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentExerciseMatchingBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.recyclerViewQuestions.adapter = questionAdapter
        binding.recyclerViewAnswers.adapter = answerAdapter
        questionAdapter.setData(exerciseMatchAnswer.option.orEmpty())
        answerAdapter.setData(exerciseMatchAnswer.option1.orEmpty())
        filteredQuestions.addAll(exerciseMatchAnswer.option.orEmpty())
        filteredAnswers.addAll(exerciseMatchAnswer.option1.orEmpty())
    }

    private fun removeQuestionAndAnswer(question: OptionMatch, answer: OptionMatch) {
        filteredQuestions.remove(question)
        filteredAnswers.remove(answer)
        questionAdapter.setData(filteredQuestions)
        questionAdapter.notifyDataSetChanged()
        answerAdapter.setData(filteredAnswers)
        answerAdapter.notifyDataSetChanged()
        answerAdapter.correctAnswerPosition = -1
        binding.recyclerViewQuestions.postDelayed({
            questionAdapter.resetSelection()
            answerAdapter.disableAllButtons()
        }, 100)
    }
}

class MatchingPagerAdapter(
    private val exerciseMatchAnswers: List<ExerciseMatchAnswer>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseMatchAnswers.size

    override fun createFragment(position: Int): Fragment = MatchingExerciseFragment()
        .apply {
            this.exerciseMatchAnswer = exerciseMatchAnswers[position]
        }
}