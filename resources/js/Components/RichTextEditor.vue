<template>
  <div v-if="editor" class="container">
    <div class="control-group">
      <div class="button-group ">
        <label @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 1 }) ? 'is-active' : ''  ]">
          H1
        </label>
        <label @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 2 }) ? 'is-active' : ''  ]">
          H2
        </label>
        <label @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 3 }) ? 'is-active' : ''  ]">
          H3
        </label>
        <label @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 4 }) ? 'is-active' : ''  ]">
          H4
        </label>
        <label @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 5 }) ? 'is-active' : ''  ]">
          H5
        </label>
        <label @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('heading', { level: 6 }) ? 'is-active' : ''  ]">
          H6
        </label>
      </div>
      <div class="button-group mt-3">
        <label @click="editor.chain().focus().toggleBulletList().run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('bulletList') ? 'is-active' : '']">
          Toggle bullet list
        </label>
        <label @click="editor.chain().focus().toggleOrderedList().run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500',  editor.isActive('orderedList') ? 'is-active' : '']">
          Toggle ordered list
        </label>
        <label @click="editor.chain().focus().splitListItem('listItem').run()" :disabled="!editor.can().splitListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Split list item
        </label>
        <label @click="editor.chain().focus().sinkListItem('listItem').run()" :disabled="!editor.can().sinkListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Sink list item
        </label>
        <label @click="editor.chain().focus().liftListItem('listItem').run()" :disabled="!editor.can().liftListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Lift list item
        </label>
      </div>
      <div class="button-group mt-3">
        <label @click="editor.chain().focus().toggleBulletList().run()" :class="['text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500', editor.isActive('bulletList') ?  'is-active': '']">
          Toggle bullet list
        </label>
        <label @click="editor.chain().focus().splitListItem('listItem').run()" :disabled="!editor.can().splitListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Split list item
        </label>
        <label @click="editor.chain().focus().sinkListItem('listItem').run()" :disabled="!editor.can().sinkListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Sink list item
        </label>
        <label @click="editor.chain().focus().liftListItem('listItem').run()" :disabled="!editor.can().liftListItem('listItem')" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Lift list item
        </label>
      </div>
      <div class="control-group mt-3">
      <div class="button-group">
        <label @click="editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Insert table
        </label>
        <label @click="editor.chain().focus().addColumnBefore().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Add column before
        </label>
        <label @click="editor.chain().focus().addColumnAfter().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Add column after
        </label>
        <label @click="editor.chain().focus().deleteColumn().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Delete column
        </label>
        <label @click="editor.chain().focus().addRowBefore().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Add row before
        </label>
        <label @click="editor.chain().focus().addRowAfter().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Add row after
        </label>
        <label @click="editor.chain().focus().deleteRow().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Delete row
        </label>
        <label @click="editor.chain().focus().deleteTable().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Delete table
        </label>
        <label @click="editor.chain().focus().mergeCells().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Merge cells
        </label>
        <label @click="editor.chain().focus().splitCell().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Split cell
        </label>
        <label @click="editor.chain().focus().toggleHeaderColumn().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Toggle header column
        </label>
        <label @click="editor.chain().focus().toggleHeaderRow().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Toggle header row
        </label>
        <label @click="editor.chain().focus().toggleHeaderCell().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Toggle header cell
        </label>
        <label @click="editor.chain().focus().mergeOrSplit().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Merge or split
        </label>
        <label @click="editor.chain().focus().setCellAttribute('colspan', 2).run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Set cell attribute
        </label>
        <label @click="editor.chain().focus().fixTables().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Fix tables
        </label>
        <label @click="editor.chain().focus().goToNextCell().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Go to next cell
        </label>
        <label @click="editor.chain().focus().goToPreviousCell().run()"  class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500">
          Go to previous cell
        </label>
      </div>
       <div class="control-group mt-3">
      <div class="button-group">
        <label @click="editor.chain().focus().toggleBulletList().run()" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500" :class="{ 'is-active': editor.isActive('bulletList') }">
          Toggle bullet list
        </label>
        <label @click="editor.chain().focus().splitListItem('listItem').run()" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500" :disabled="!editor.can().splitListItem('listItem')">
          Split list item
        </label>
        <label @click="editor.chain().focus().sinkListItem('listItem').run()" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500" :disabled="!editor.can().sinkListItem('listItem')">
          Sink list item
        </label>
        <label @click="editor.chain().focus().liftListItem('listItem').run()" class="text-white border border-white px-3 py-3 mr-2  hover:text-green-500 hover:border-green-500" :disabled="!editor.can().liftListItem('listItem')">
          Lift list item
        </label>
      </div>
    </div>
    </div>
    <div class="w-full mt-5">
      <label class="text-white">Text Editor</label>
    </div>
    <editor-content :editor="editor" id="rich-text-editor"></editor-content>
     
     
  </div>
  </div>
</template>

<script setup>
import Document from '@tiptap/extension-document'
import Heading from '@tiptap/extension-heading'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import ListItem from '@tiptap/extension-list-item'
import OrderedList from '@tiptap/extension-ordered-list'
import BulletList from '@tiptap/extension-bullet-list'
import Table from '@tiptap/extension-table'
import TableCell from '@tiptap/extension-table-cell'
import TableHeader from '@tiptap/extension-table-header'
import TableRow from '@tiptap/extension-table-row'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import { onMounted, ref } from 'vue';


const props = defineProps({
    modelValue: String
})
const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue || '',
    extensions: [ 
        Document,
        Paragraph,
        Text,
        Heading.configure({
            levels: [1, 2, 3, 4, 5, 6],
        }),
        BulletList,
        OrderedList,
        ListItem,
        Table.configure({
            resizable: true,
        }),
        TableRow,
        TableHeader,
        TableCell,
    ],
    onUpdate({ editor }) {
        emit('update:modelValue', editor.getHTML())
    },
    editorProps: {
    attributes: {
      class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none text-white border border-white px-3 py-3 mr-2 mt-5 min-h-[40vh]',
      
    },
  },
    
  
});
onMounted(() => {
  emit('update:modelValue', editor.value.getHTML())
});

</script>
<style>
button[disabled]{
  opacity: 0.5;
}
#rich-text-editor h1{
  font-size: 25px;
}
#rich-text-editor h2{
  font-size: 23px;
}
#rich-text-editor h3{
  font-size: 21px;
}
#rich-text-editor h4{
  font-size: 19px;
}
#rich-text-editor h5{
  font-size: 17px;
}
#rich-text-editor h6{
  font-size: 15px;
}
#rich-text-editor a{
  text-decoration: underline;
}
#rich-text-editor table
#rich-text-editor table td,
#rich-text-editor table tbody tr td,
#rich-text-editor table th{
  border:1px solid #fff ;
}
.control-group label{
  display: inline-block;
}
</style>