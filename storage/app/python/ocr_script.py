import sys
import os
import gc
import logging
from paddleocr import PaddleOCR
from PIL import Image

# Set up logging
logging.basicConfig(level=logging.INFO)

def split_image(image_path, num_rows=2, num_cols=2):
    """Split the image into smaller parts."""
    img = Image.open(image_path)
    width, height = img.size
    part_width = width // num_cols
    part_height = height // num_rows
    parts = []

    # Create and save each part
    for i in range(num_rows):
        for j in range(num_cols):
            left = j * part_width
            upper = i * part_height
            right = left + part_width
            lower = upper + part_height

            part = img.crop((left, upper, right, lower))
            part_path = image_path.replace('.png', f'_part_{i}_{j}.png')
            part.save(part_path)
            parts.append(part_path)

    return parts

def extract_text(image_path, ocr):
    """Extract text from an image using PaddleOCR."""
    logging.info(f"Processing image: {image_path}")
    try:
        # Perform OCR
        result = ocr.ocr(image_path, cls=True)

        # Extract text from OCR result
        extracted_text = ''
        for line in result[0]:
            word_info = line[1]
            word_text = word_info if isinstance(word_info, str) else str(word_info[0])
            extracted_text += word_text + '\n'

        return extracted_text

    except Exception as e:
        logging.error(f"Error processing {image_path}: {str(e)}")
        return ''  # Return empty text if OCR fails on this part

def save_text_to_file(image_path, extracted_text):
    """Save the extracted text to a file."""
    output_file = image_path.replace('.png', '.txt')
    try:
        with open(output_file, 'w') as f:
            f.write(extracted_text)
        logging.info(f"Extracted text saved to: {output_file}")
    except Exception as e:
        logging.error(f"Error saving text to file {output_file}: {str(e)}")

def process_image(image_path, ocr):
    """Process a single image by splitting, extracting text, and saving to file."""
    parts = split_image(image_path)  # Split the image into parts
    full_text = ''

    for part in parts:
        extracted_text = extract_text(part, ocr)
        full_text += extracted_text

        # Clean up memory after processing each part
        del extracted_text
        gc.collect()

    # Save the extracted text to a file
    save_text_to_file(image_path, full_text)
    return full_text

if __name__ == '__main__':
    # Initialize PaddleOCR once
    ocr = PaddleOCR(use_angle_cls=True, lang='en', det_algorithm='DB', rec_algorithm='CRNN', rec_batch_num=1,  table=False, formula=False, layout=False, max_text_length=10, cpu_threads=1)

    # Get a list of image paths from command line arguments
    image_paths = sys.argv[1:]

    for image_path in image_paths:
        # Process each image individually
        logging.info(f"Starting processing for {image_path}")
        extracted_text = process_image(image_path, ocr)

        # Optionally print the extracted text for each image
        print(extracted_text)

        # Force garbage collection to free memory explicitly after each image
        gc.collect()

    # Clean up PaddleOCR instance
    del ocr
    gc.collect()
