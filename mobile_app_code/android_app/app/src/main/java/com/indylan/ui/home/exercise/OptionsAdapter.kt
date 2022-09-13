package com.indylan.ui.home.exercise

import android.graphics.drawable.Drawable
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatTextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.indylan.R
import com.indylan.data.model.OptionTranslation
import com.indylan.ui.home.exercise.base.BaseAdapter
import com.indylan.ui.home.exercise.base.BaseViewHolder

class OptionsAdapter(
    private val recyclerView: RecyclerView,
    callback: (Int, OptionTranslation) -> Unit
) : BaseAdapter<OptionTranslation, BaseViewHolder<OptionTranslation>>(
    recyclerView,
    false,
    callback
) {

    var correctAnswerPosition: Int = -1

    fun findCorrectAnswerPosition(): Int {
        getItems().forEachIndexed { index, answer ->
            if (answer.isCorrect == 1) {
                return index
            }
        }
        return -1
    }

    override fun createHolder(
        parent: ViewGroup,
        callback: (Int, OptionTranslation) -> Unit
    ): BaseViewHolder<OptionTranslation> {
        val view =
            LayoutInflater.from(recyclerView.context).inflate(R.layout.item_text, parent, false)
        return TextAnswerViewHolder(view, callback)
    }

    override fun bind(item: OptionTranslation, holder: BaseViewHolder<OptionTranslation>) {
        (holder as? TextAnswerViewHolder)?.bindData(item)
    }

    inner class TextAnswerViewHolder(
        view: View,
        rightCallback: (Int, OptionTranslation) -> Unit
    ) : BaseViewHolder<OptionTranslation>(view, rightCallback) {

        private val textView = view.findViewById<AppCompatTextView>(R.id.textView)

        fun bindData(option: OptionTranslation) {
            textView.text = option.word
            textView.tag = option
        }

        override fun getViewToAnimate(): View = textView

        override fun background(): Drawable? =
            ContextCompat.getDrawable(textView.context, R.drawable.bg_pink_border)
    }
}