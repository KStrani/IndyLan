package com.indylan.ui.home.exercise

import android.graphics.drawable.Drawable
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatTextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.indylan.R
import com.indylan.data.model.OptionMatch
import com.indylan.ui.home.exercise.base.BaseAdapter
import com.indylan.ui.home.exercise.base.BaseViewHolder

class OptionsMatchAdapter(
    private val recyclerView: RecyclerView,
    private val isQuestion: Boolean,
    callback: (Int, OptionMatch) -> Unit
) : BaseAdapter<OptionMatch, BaseViewHolder<OptionMatch>>(recyclerView, true, callback) {

    var correctAnswerPosition: Int = -1

    fun findCorrectAnswerPosition(wordId: String?): Int {
        getItems().forEachIndexed { index, answer ->
            if (answer.wordId == wordId) {
                return index
            }
        }
        return -1
    }

    override fun createHolder(
        parent: ViewGroup,
        callback: (Int, OptionMatch) -> Unit
    ): BaseViewHolder<OptionMatch> {
        val view =
            LayoutInflater.from(recyclerView.context).inflate(R.layout.item_text, parent, false)
        val background = if (isQuestion) {
            ContextCompat.getDrawable(view.context, R.drawable.bg_blue_chat)
        } else {
            ContextCompat.getDrawable(view.context, R.drawable.bg_pink_border)
        }
        return TextAnswerViewHolder(view, background, callback)
    }

    override fun bind(item: OptionMatch, holder: BaseViewHolder<OptionMatch>) {
        (holder as? TextAnswerViewHolder)?.bindData(item)
    }

    inner class TextAnswerViewHolder(
        view: View,
        private val background: Drawable?,
        rightCallback: (Int, OptionMatch) -> Unit
    ) : BaseViewHolder<OptionMatch>(view, rightCallback) {

        private val textView = view.findViewById<AppCompatTextView>(R.id.textView).apply {
            background = background()
        }

        fun bindData(option: OptionMatch) {
            textView.text = option.word
            textView.tag = option
        }

        override fun getViewToAnimate(): View = textView

        override fun background(): Drawable? = background
    }
}