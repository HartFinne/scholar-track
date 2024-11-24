import sys
from paddleocr import PaddleOCR
import logging
from PIL import Image
import psutil
import time
import gc

# Set up logging
logging.basicConfig(level=logging.INFO)

def get_available_memory():
    """Get the available memory on the server in MB."""
    memory = psutil.virtual_memory()
    return memory.available / (1024 ** 2)  # Return available memory in MB

def resize_image(image_path, max_size=500):
    """Resize the image to a smaller size."""
    img = Image.open(image_path)
    img.thumbnail((max_size, max_size))  # Reduce image size to 500x500 or smaller
    resized_image_path = image_path.replace('.png', '_resized.png')
    img.save(resized_image_path)
    return resized_image_path


def extract_text(image_path):
    """Extract text from an image using PaddleOCR."""
    logging.info(f"Processing image: {image_path}")
    
    try:
        # Resize image before OCR
        image_path = resize_image(image_path)
        
        # Initialize PaddleOCR with language 'en' (English)
        ocr = PaddleOCR(use_angle_cls=True, lang='en', det_algorithm='DB', rec_algorithm='CRNN', rec_batch_num=1,  table=False, formula=False, layout=False, max_text_length=10, cpu_threads=1)

        result = ocr.ocr(image_path, cls=True)

        extracted_text = ''
        for line in result[0]:
            word_info = line[1]
            word_text = word_info if isinstance(word_info, str) else str(word_info[0])
            extracted_text += word_text + '\n'

        return extracted_text

    except Exception as e:
        logging.error(f"Error processing {image_path}: {str(e)}")
        return ''

def save_text_to_file(image_path, extracted_text):
    """Save the extracted text to a file."""
    output_file = image_path.replace('.png', '.txt')  # Save as a text file
    try:
        with open(output_file, 'w') as f:
            f.write(extracted_text)
        logging.info(f"Extracted text saved to: {output_file}")
    except Exception as e:
        logging.error(f"Error saving text to file {output_file}: {str(e)}")

def process_images(image_paths):
    ocr = PaddleOCR(use_angle_cls=True, lang='en', det=False)  # Set det=False
    
    texts = []
    for image_path in image_paths:
        result = ocr.ocr(image_path, cls=True)
        
        # Explicitly clear memory after each image is processed
        del ocr
        gc.collect()  # Force garbage collection to free up memory
        
        # Process the result and extract text
        extracted_text = ''
        for line in result[0]:
            word_info = line[1]
            word_text = word_info if isinstance(word_info, str) else str(word_info[0])
            extracted_text += word_text + '\n'
        
        texts.append(extracted_text)
    
    return texts

if __name__ == '__main__':
    # Get a list of image paths from command line arguments
    image_paths = sys.argv[1:]

    # Process the images and extract text
    texts = process_images(image_paths)
    
    # Save extracted text to files
    for image_path, text in zip(image_paths, texts):
        save_text_to_file(image_path, text)
        
    # Print out the extracted text for each image (optional)
    for text in texts:
        print(text)
