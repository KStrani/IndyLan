package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.R
import com.indylan.data.model.DialogList
import com.indylan.data.model.ExerciseDialog
import com.indylan.data.model.ExerciseType
import com.indylan.data.model.ExerciseTypeEnum
import com.indylan.databinding.FragmentExerciseDialogBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class DialogExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseDialogBinding
    lateinit var exerciseDialog: ExerciseDialog
    lateinit var exerciseType: ExerciseType
    private var answeredWrong = false
    private val chatItemAdapter: ChatItemAdapter by lazy {
        ChatItemAdapter {
            (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
        }
    }
    private val chatItemSelectionAdapter: ChatItemSelectionAdapter by lazy {
        ChatItemSelectionAdapter {
            if (it.isCorrect) {
                chatItemAdapter.addNewItem(it)
                setNextSelectionOptions(chatItemAdapter.itemCount, exerciseDialog.list)
            } else {
                answeredWrong = true
            }
        }
    }

    override fun onFocusGained() {
        view?.doOnLayout {
            when (exerciseType.parseExerciseType()) {
                ExerciseTypeEnum.TEXT_CHAT_VIEW_ONLY -> {
                    (parentFragment as ExerciseFragment).showButton()
                }
                ExerciseTypeEnum.MULTIPLE_CHOICE_CHAT_SELECTION -> {
                    (parentFragment as ExerciseFragment).showExerciseTypeName()
                }
                else -> {
                }
            }
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseDialogBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            textViewTitle.text = exerciseDialog.title
            when (exerciseType.parseExerciseType()) {
                ExerciseTypeEnum.TEXT_CHAT_VIEW_ONLY -> {
                    recyclerViewDialog.adapter = chatItemAdapter
                    chatItemAdapter.submitItems(exerciseDialog.list)
                }
                ExerciseTypeEnum.MULTIPLE_CHOICE_CHAT_SELECTION -> {
                    recyclerViewDialog.adapter = chatItemAdapter
                    chatItemAdapter.submitItems(
                        if (exerciseDialog.list?.size ?: 0 > 2)
                            exerciseDialog.list?.subList(0, 2)
                        else
                            exerciseDialog.list
                    )
                    recyclerViewDialogSelection.adapter = chatItemSelectionAdapter
                    setNextSelectionOptions(chatItemAdapter.itemCount, exerciseDialog.list)
                }
                else -> {
                }
            }
            audioView.isVisible =
                exerciseDialog.isAudioAvailable == "1" && !exerciseDialog.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(exerciseDialog.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
        }
        return binding.root
    }

    private fun setNextSelectionOptions(currentAnswered: Int, list: List<DialogList>?) {
        if (list != null && currentAnswered < list.size - 1) {
            val nextAnswer = list[currentAnswered]
            nextAnswer.isCorrect = true
            val nextSelectionOptions = mutableListOf<DialogList>()
            nextSelectionOptions.add(nextAnswer)
            nextSelectionOptions.addAll(
                list.subList(currentAnswered + 1, list.size).shuffled().take(3)
            )
            chatItemSelectionAdapter.submitItems(nextSelectionOptions.shuffled())
        } else {
            list?.lastOrNull()?.let {
                chatItemAdapter.addNewItem(it)
            }
            chatItemSelectionAdapter.submitItems(emptyList())
            if (!answeredWrong) {
                (parentFragment as ExerciseFragment).increaseScore()
            }
            (parentFragment as ExerciseFragment).showButton()
        }
    }
}

class DialogPagerAdapter(
    private val exerciseType1: ExerciseType,
    private val exerciseDialogs: List<ExerciseDialog>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseDialogs.size

    override fun createFragment(position: Int): Fragment = DialogExerciseFragment()
        .apply {
            this.exerciseDialog = exerciseDialogs[position]
            this.exerciseType = exerciseType1
        }
}