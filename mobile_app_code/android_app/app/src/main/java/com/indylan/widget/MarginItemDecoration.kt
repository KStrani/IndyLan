package com.indylan.widget

import android.graphics.Rect
import android.view.View
import androidx.recyclerview.widget.RecyclerView

class MarginItemDecoration(
    private val spaceHeight: Int,
    private val count: Int = 1
) :
    RecyclerView.ItemDecoration() {

    override fun getItemOffsets(
        outRect: Rect,
        view: View,
        parent: RecyclerView,
        state: RecyclerView.State
    ) {
        with(outRect) {
            //Timber.d("Position: ${parent.getChildAdapterPosition(view)}")
            //Timber.d("Child Count: ${parent.adapter?.itemCount}")
            top = if (parent.getChildAdapterPosition(view) < count) {
                spaceHeight * 2
            } else {
                spaceHeight / 2
            }
            if (count == 1) {
                left = spaceHeight * 3
                right = spaceHeight * 3
            } else {
                left = if ((parent.getChildAdapterPosition(view) + 1) % count == 0)
                    spaceHeight
                else
                    spaceHeight * 3
                right = if ((parent.getChildAdapterPosition(view) + 1) % count == 0)
                    spaceHeight * 3
                else
                    spaceHeight
            }
            bottom = spaceHeight
            /*bottom = if (parent.getChildAdapterPosition(view) == parent.childCount - 1) {
                spaceHeight * 2
            } else {
                spaceHeight / 2
            }*/
            //Timber.d("Top: $top , Bottom: $bottom , Left: $left , Right: $right")
        }
    }
}